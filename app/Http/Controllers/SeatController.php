<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Row;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class SeatController extends Controller
{
    public function index()
    {
        $rows = Row::all();
        $columns = Column::all();
        $seats = Seat::all();

        return view('seats', compact('seats', 'rows', 'columns'));
    }

    public function bookSeats(Request $request)
    {
        DB::beginTransaction();
        try {
            $seatId = $request->seatId;
            $seatCount = $request->seatCount;
            $seat = Seat::findOrFail($seatId);
            $bookingCount = Seat::where([['booking_status', '!=', 1], ['row_id', '=', $seat->row_id]])->count();
            if($bookingCount < $seatCount) {
                $message = $this->failedResponse($seatCount);
                return redirect()->route('seats.index')->withErrors($message)->withInput();
            }

            $adjacent = floor($seatCount/2);
            if($adjacent > 0) {

                list($left, $seatArray, $seatsAvailable, $right) = $this->seatLogic(true, 1, $seatCount, $adjacent, $seat, [$seat->id], true);

                if($seatsAvailable < $seatCount) {
                    if(!$left && !$right) {
                        $message = $this->failedResponse($seatCount);
                        return redirect()->route('seats.index')->withErrors($message)->withInput();
                    }

                    list($left, $seatArray, $seatsAvailable, $right) = $this->seatLogic($left, $seatsAvailable, $seatCount, $seatCount, $seat, $seatArray, $right, $adjacent);
                }

                if(!$left && !$right) { // OR if($seatsAvailable < $seatCount)
                    $message = $this->failedResponse($seatCount);
                    return redirect()->route('seats.index')->withErrors($message)->withInput();
                }

                Seat::whereIn('id', $seatArray)->update(['booking_status' => 1]);
            } else {
                if($seat->booking_status != 1) {
                    $seat->booking_status = 1;
                    $seat->save();
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error = $e->getMessage();
            return redirect()->route('seats.index')->withErrors($error)->withInput();
        }
        DB::commit();
        return redirect()->route('seats.index')->with('success', 'Booking created successfully');
    }

    public function seatLogic(bool $left, $seatsAvailable, $seatCount, $total, $seat, array $seatArray, bool $right, $start = 0): array
    {
        for($i=$start; $i<$total; $i++) {
            if ($left && $seatsAvailable < $seatCount) {
                $prevSeat = Seat::where([['id', '<', $seat->id], ['row_id', '=', $seat->row_id]])->orderBy('id', 'DESC')->skip($i)->first();
                if (isset($prevSeat)) {
                    if ($prevSeat->booking_status == 1) {
                        $left = false;
                    } else {
                        $seatArray[] = $prevSeat->id;
                        ++$seatsAvailable;
                    }
                } else {
                    $left = false;
                }
            }

            if ($right && $seatsAvailable < $seatCount) {
                $nextSeat = Seat::where([['id', '>', $seat->id], ['row_id', '=', $seat->row_id]])->orderBy('id', 'ASC')->skip($i)->first();
                if (isset($nextSeat)) {
                    if ($nextSeat->booking_status == 1) {
                        $right = false;
                    } else {
                        $seatArray[] = $nextSeat->id;
                        ++$seatsAvailable;
                    }
                } else {
                    $right = false;
                }
            }
        }
        return array($left, $seatArray, $seatsAvailable, $right);
    }

    public function failedResponse($seatCount): String
    {
        $message = 'Enough seats are not available in this row. Please select seats in another row.';
        $suggestedSeats = $this->seatSuggestions($seatCount);
        if(!empty($suggestedSeats)) {
            $suggestedSeats = implode(', ', $suggestedSeats);
            $message .= " Some suggestions are $suggestedSeats.";
        }
        return $message;
    }

    public function seatSuggestions($total): array
    {
        $rows = Row::all();
        foreach ($rows as $row) {
            $totalAvailableSeats = Seat::where([['row_id', '=', $row->id], ['booking_status', '!=', 1]])->count();
            if($totalAvailableSeats < $total) {
                continue;
            }
            $firstAvailableSeat = Seat::where([['row_id', '=', $row->id], ['booking_status', '!=', 1]])->first();
            $availableColumns = Column::where('id', '>=', $firstAvailableSeat->column_id)->get();
            $seatsAvailable = 0;
            $seatArray = [];
            foreach ($availableColumns as $column) {
                $currSeat = Seat::where([['column_id', '=', $column->id], ['row_id', '=', $row->id], ['booking_status', '!=', 1]])->first();
                if(isset($currSeat)) {
                    $seatArray[] = $currSeat->name;
                    ++$seatsAvailable;
                } else {
                    $seatsAvailable = 0;
                    $seatArray = [];
                }

                if($seatsAvailable == $total) {
                    return $seatArray;
                }
            }
        }
        return [];
    }
}
