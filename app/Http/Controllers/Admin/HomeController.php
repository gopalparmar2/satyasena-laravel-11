<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Caste;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    public function index() {
        try {
            $data = [];
            $data['page_title'] = 'Dashboard';

            $data['breadcrumb'][] = array(
                'link' => route('admin.index'),
                'title' => 'Dashboard'
            );

            $data['breadcrumb'][] = array(
                'title' => 'Dashboard'
            );

            $data['total_users'] = User::whereStatus(1)->count();
            $data['total_categories'] = Category::whereStatus(1)->count();
            $data['total_castes'] = Caste::whereStatus(1)->count();
            $data['total_surnames'] = User::whereStatus(1)->whereNotNull('last_name')->distinct('last_name')->count();

            return view('admin.dashboard', $data);
        } catch (\Exception $e) {
            return abort(404);
        }
    }

    public function getColorChartData(Request $request) {
        try {
            if ($request->ajax()) {
                $green = User::whereStatus(1)->where('color_id', 1)->count();
                $white = User::whereStatus(1)->where('color_id', 2)->count();
                $red = User::whereStatus(1)->where('color_id', 3)->count();

                $series = [$green, $red, $white];

                $response = [
                    'success' => true,
                    'series' => $series
                ];

                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];

            return response()->json($response);
        }
    }

    public function getCasteChartData(Request $request) {
        try {
            if ($request->ajax()) {
                $castes = User::whereStatus(1)->whereNotNull('caste_id')->groupBy('caste_id')->get();

                $labels = [];
                $series = [];

                foreach ($castes as $caste) {
                    $labels[] = $caste->caste->name;
                    $series[] = User::whereStatus(1)->where('caste_id', $caste->caste_id)->count();
                }

                $response = [
                    'success' => true,
                    'series' => $series,
                    'labels' => $labels
                ];

                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];

            return response()->json($response);
        }
    }

    public function getSurnameChartData(Request $request) {
        try {
            if ($request->ajax()) {
                $surnames = User::whereStatus(1)->whereNotNull('last_name')->groupBy('last_name')->get();

                $labels = [];
                $series = [];

                foreach ($surnames as $surname) {
                    $labels[] = $surname->last_name;
                    $series[] = User::whereStatus(1)->where('last_name', $surname->last_name)->count();
                }

                $response = [
                    'success' => true,
                    'series' => $series,
                    'labels' => $labels
                ];

                return response()->json($response);
            }
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];

            return response()->json($response);
        }
    }
}
