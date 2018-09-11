<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\Series;
use Illuminate\Http\Request;

class WatchSeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Series $series)
    {
        $user = auth()->user();
        // dd($series->lessons);
        // dd($user->getNextLessonToWatch($series));
        if($user->hasStartedSeries($series)) {
            return redirect()->route('series.watch', [
                'series' => $series->slug,
                'lesson' => $user->getNextLessonToWatch($series)->id //$lesson
            ]);
        }

        return redirect()->route('series.watch', [
            'series' => $series->slug,
            'lesson' => $series->lessons->first()->id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLesson(Series $series, Lesson $lesson)
    {
        return view('watch', [
            'series' => $series,
            'lesson' => $lesson
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function completeLesson(Lesson $lesson)
    {
        auth()->user()->completeLesson($lesson);
        return response()->json([
            'status' => 'ok'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
