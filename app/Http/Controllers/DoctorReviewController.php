<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorReview;
use App\Models\Doctor;
use App\User;
use Notification;
use App\Notifications\StatusNotification;


class DoctorReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $reviews = DoctorReview::getAllReview();
        return view('backend.ratedoctor.index', compact('reviews'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
            $validatedData = $request->validate([
                'doctorID' => 'required|exists:Doctors,doctorID',
                'rate' => 'required|numeric|min:1|max:5',
                'review' => 'required|string|max:255',
            ]);

            $validatedData['user_id'] = auth()->id();
            $validatedData['status'] = 'active';


            DoctorReview::create($validatedData);

            return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = DoctorReview::findOrFail($id);
        return view('backend.ratedoctor.edit')->with('review', $review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $review = DoctorReview::findOrFail($id);

        $request->validate([
            'review' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $review->review = $request->review;
        $review->status = $request->status;

        if ($review->save()) {
            $request->session()->flash('success', 'Đánh giá đã được cập nhật.');
        } else {
            $request->session()->flash('error', 'Có lỗi xảy ra! Vui lòng thử lại.');
        }

        return redirect()->route('doctor-review.index');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = DoctorReview::findOrFail($id);
        $status = $review->delete();

        if ($status) {
            request()->session()->flash('success', 'Đánh giá đã được xóa.');
        } else {
            request()->session()->flash('error', 'Có lỗi xảy ra! Vui lòng thử lại.');
        }

        return redirect()->route('backend.ratedoctor.index');
    }
}
