<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Video;
class VideoController extends Controller
{
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'chunkIndex' => 'required|integer',
            'totalChunks' => 'required|integer',
            'uploadId' => 'required|string',
            'fileName' => 'required|string',
        ]);

        $uploadFolder = 'video_chunks/' . $request->uploadId;
        $chunkPath = $uploadFolder . '/' . $request->chunkIndex;

        Storage::disk('local')->put($chunkPath, file_get_contents($request->file('file')));

        return response()->json(['message' => 'Chunk uploaded']);
    }



    public function mergeChunks(Request $request)
    {

        $request->validate([
            'uploadId' => 'required|string',
            'fileName' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);


        $uploadFolder = storage_path('app/video_chunks/' . $request->uploadId);
        $finalFileName = time() . '_' . $request->fileName;
        $finalPath = 'videos/' . $finalFileName;

        $chunks = collect(scandir($uploadFolder))
            ->filter(fn($name) => is_numeric($name))
            ->sort()
            ->values();

        $finalVideo = fopen(storage_path('app/' . $finalPath), 'ab');


        foreach ($chunks as $chunk) {
            $chunkContent = file_get_contents($uploadFolder . '/' . $chunk);
            fwrite($finalVideo, $chunkContent);
        }

        // Clean up chunks
        Storage::deleteDirectory('video_chunks/' . $request->uploadId);

        // 🔐 Get the user from JWT token
        $user = auth()->user();

        // Save to database
        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'video_path' => $finalPath,
            'user_id' => $user->id, // 👈 taken from JWT
        ]);

        return response()->json(['message' => 'Video uploaded successfully', 'video' => $video]);
    }


    public function getUserVideos(Request $request)
    {

        try {
            $user = auth()->user();

            // Fetch videos for the authenticated user
            $videos = Video::where('user_id', $user->id)->get();

            return response()->json([
                "error" => false,
                'message' => 'Videos fetched successfully',
                'videos' => $videos
            ]);


        } catch (\Exception $e) {
            return response()->json([
                "error"=> true,
                'message' => 'Error fetching videos',
                "videos"=> []
            ], 500);
        }
    }


    public function getAllVideos(Request $request){
        try{
            //Get limit and offset from request
            $limit = $request->query('limit', 10);
            $offset = $request->query('offset', 0);

            // Fetch all videos with pagination
            $videos = Video::with('user')
                ->skip($offset)
                ->take($limit)
                ->orderBy('created_at', 'desc')
                ->get();

            $total = Video::count();
            return response()->json([
                "error" => false,
                "message"=> 'Videos fetched successfully',
                "videos" => $videos,
                "total" => $total,
                "limit" => $limit,  
                "offset" => $offset
            ]);

        }catch(\Exception $e){
            return response()->json([
                "error"=> true,
                'message' => 'Error fetching videos',
                "videos"=> []
            ], 500);
        }
    }
}
