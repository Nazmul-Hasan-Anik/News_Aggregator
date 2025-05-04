<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    CONST FILTER = ['title', 'published_at', 'category'];
    CONST ORDER = 'asc';
    public function allArticles()
    {
        try {
            $category = FacadesRequest::input('category');
            $source = FacadesRequest::input('source');
            $published_at = FacadesRequest::input('published_at');
            $search = FacadesRequest::input('search');
            $search = addcslashes($search, '%_');
            $sort_by = in_array(FacadesRequest::input('sort_by'), self::FILTER) ? FacadesRequest::input('sort_by') : 'published_at';
            $order = in_array(FacadesRequest::input('order'), ['asc', 'desc']) ? FacadesRequest::input('order') : 'asc';
            $articles = DB::table('articles')
                ->when($category, function ($query) use ($category) {
                    // dd($category);
                    return $query->where('category', $category);
                })
                ->when($source, function ($query) use ($source) {
                    return $query->where('source', $source);
                })
                ->when($published_at, function ($query) use ($published_at) {
                    return $query->whereDate('published_at', $published_at);
                })
                ->when($search, function ($query) use ($search) {
                    return $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->orderBy($sort_by, $order)
                ->paginate(10);

            return response()->json($articles);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while fetching articles',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Logic to store a new article
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'category' => 'nullable|string|max:255',
            'source' => 'required|string|max:255',
        ]);
        $article = Article::create($validatedData);
        return response()->json($article->only(['id','title','description','content','published_at','category','source']), 201);
    }

    public function specificArticle($id)
    {
        // Logic to fetch a specific article by ID
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }
        return response()->json($article);
    }
}
