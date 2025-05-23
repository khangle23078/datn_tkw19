<?php

namespace App\Http\Controllers\Seeker;

use App\Enums\StatusCode;
use App\Http\Controllers\BaseController;
use App\Models\Company;
use App\Models\Employer;
use App\Models\Experience;
use App\Models\Favourite;
use App\Models\Job;
use App\Models\Jobseeker;
use App\Models\Jobskill;
use App\Models\Lever;
use App\Models\location;
use App\Models\Majors;
use App\Models\Profession;
use App\Models\SeekerSkill;
use App\Models\Skill;
use App\Models\Timework;
use App\Models\UploadCv;
use App\Models\User;
use App\Models\Wage;
use App\Models\WorkingForm;
use Database\Seeders\SkillSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function GuzzleHttp\Promise\all;

class HomeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public Lever $lever;
    public Experience $experience;
    public Wage $wage;
    public Skill $skill;
    public Timework $timework;
    public Profession $profession;
    public Job $job;
    public Jobskill $jobskill;
    public Majors $majors;
    public Employer $employer;
    public Company $company;
    public location $location;
    public WorkingForm $workingform;
    public User $user;
    public SeekerSkill $SeekerSkill;
    public Jobseeker $Jobseeker;
    public UploadCv $upload;

    public function __construct(UploadCv $upload, Jobseeker $Jobseeker, User $user, Lever $lever, Experience $experience, Wage $wage, Skill $skill, Timework $timework, Profession $profession, Jobskill $jobskill, Job $job, Majors $majors, Employer $employer, Company $company, location $location, WorkingForm $workingform, SeekerSkill $SeekerSkill)
    {
        $this->lever = $lever;
        $this->experience = $experience;
        $this->wage = $wage;
        $this->skill = $skill;
        $this->timework = $timework;
        $this->profession = $profession;
        $this->job = $job;
        $this->jobskill = $jobskill;
        $this->majors = $majors;
        $this->employer = $employer;
        $this->company = $company;
        $this->location = $location;
        $this->workingform = $workingform;
        $this->user = $user;
        $this->SeekerSkill = $SeekerSkill;
        // $this->Jobseer = $SeekerSkill;
        $this->Jobseeker = $Jobseeker;
        $this->upload = $upload;
    }
    public function index()
    {
        $breadcrumbs = [
            'Thông tin cá nhân'
        ];
        $user =  $this->user
            ->with('getProfileUse')
            ->where('users.id', Auth::guard('user')->user()->id)
            ->first();
        $getskill = $this->Jobseeker->with('getskill')->where('user_role', Auth::guard('user')->user()->id)->first();
        $cv = UploadCv::where('user_id', Auth::guard('user')->user()->id)->get();

        return view('client.seeker.profile', [
            'user' => $user,
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Thông tin cá nhân',
            'lever' => $this->getlever(),
            'experience' => $this->getexperience(),
            'wage' => $this->getwage(),
            'skill' => $this->getskill(),
            'timework' => $this->gettimework(),
            'profession' => $this->getprofession(),
            'majors' => $this->getmajors(),
            'location' => $this->getlocation(),
            'workingform' => $this->getworkingform(),
            'getskill' => $getskill,
            'cv' => $cv,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $upload = new $this->upload();
            $upload->user_id = Auth::guard('user')->user()->id;
            $upload->title = Auth::guard('user')->user()->name;
            if ($request->hasFile('file_cv')) {
                $upload->file_cv = $request->file_cv->storeAs('images/cv', $request->file_cv->hashName());
            }
            $upload->save();
            $this->setFlash(__('Thêm cv mới thành công'));
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setFlash(__('đã có một lỗi không rõ nguyên nhân xảy ra'), 'error');
            return redirect()->back();
        }
        // $Jobseeker = $this->user->where('id', Auth::guard('user')->user()->id)->first();
        // if (isset($Jobseeker->getProfileUse)) {
        //     $user = $this->Jobseeker->where('user_role', Auth::guard('user')->user()->id)->first();
        // } else {
        //     $user = new $this->Jobseeker();
        // }
        // try {
        //     $updateUser = $this->user->where('id', Auth::guard('user')->user()->id)->first();
        //     $updateUser->name = $request->name;
        //     $updateUser->email = $request->email;
        //     $updateUser->save();
        //     $user->address = $request->address;
        //     if ($request->hasFile('images')) {
        //         $user->images = $request->images->storeAs('images/cv', $request->images->hashName());
        //     }
        //     $user->phone = $request->phone;
        //     $user->user_role = Auth::guard('user')->user()->id;
        //     $user->experience_id = $request->experience_id;
        //     $user->lever_id = $request->lever_id;
        //     $user->wage_id = $request->wage_id;
        //     $user->profession_id = $request->profession_id;
        //     $user->time_work_id = $request->time_work_id;
        //     $user->save();
        //     if (isset($Jobseeker->getProfileUse)) {
        //         $jobskill =  $this->SeekerSkill->where('job-seeker_id', $user->id)->get();
        //         foreach ($jobskill as $value) {
        //             $this->SeekerSkill->find($value->id)->delete();
        //         }
        //     }
        //     foreach (explode(',', $request->skill[0]) as $value) {
        //         $this->SeekerSkill->create([
        //             'job-seeker_id' => $user->id,
        //             'skill_id' => $value,
        //         ])->save();
        //     }
        //     $this->setFlash(_('Cập nhật thành công!'));
        //     return redirect()->back();
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     $this->setFlash(_('Đã có một lỗi xảy ra'), 'error');
        //     return redirect()->back();
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
    public function userProfile()
    {
    }
    public function logout()
    {
        Auth::guard('user')->logout();
        return redirect()->route('index');
    }
    public function userFavourite()
    {
        $breadcrumbs = [
            'Tin tuyển dụng yêu thích'
        ];
        $job = Job::join('favourite', 'favourite.job_id', '=', 'job.id')
            ->join('employer', 'employer.id', '=', 'job.employer_id')
            ->join('company', 'company.id', '=', 'employer.id_company')
            ->where('favourite.user_id', Auth::guard('user')->user()->id)
            ->select('favourite.*', 'company.logo as logo', 'company.name as nameCompany', 'company.id as idCompany', 'employer.id as idEmployer', 'job.title as title')
            ->get();
        return view('client.seeker.favourite', [
            'breadcrumbs' => $breadcrumbs,
            'job' => $job,
            'majors' => $this->getmajors(),
        ]);
    }
    public function userShowFavouriteId($id)
    {
        return response()->json([
            'job' => $this->job
                ->join('employer', 'employer.id', '=', 'job.employer_id')
                ->join('company', 'company.id', '=', 'employer.id_company')
                ->select('job.*', 'company.logo as logo')
                ->with(['getLevel', 'getExperience', 'getWage', 'getprofession', 'getlocation', 'getMajors', 'getwk_form', 'getTime_work', 'getskill'])
                ->where('job.id', $id)->first()
        ]);
    }
    public function userFavouriteId($id)
    {
        $favourite = Favourite::select('*')->get();
        if ($favourite->where('user_id', Auth::guard('user')->user()->id)->whereIn('job_id', $id)->first()) {
            Favourite::where('job_id', $id)->delete();
            return response()->json([
                'status' => StatusCode::OK
            ]);
        }
        Favourite::create([
            'job_id' => $id,
            'user_id' => Auth::guard('user')->user()->id,
        ])->save();
        return response()->json([
            'status' => StatusCode::OK
        ]);
    }
    public function deleteFavourite($id)
    {
        if (Favourite::where('id', $id)->delete()) {
            return response()->json([
                'message' => 'Xóa công việc thành công',
                'status' => StatusCode::OK,
            ], StatusCode::OK);
        }
        return response()->json([
            'message' => 'một lỗi đã xảy ra',
            'status' => StatusCode::OK,
        ], StatusCode::INTERNAL_ERR);
    }
    public function changePassword()
    {
        $breadcrumbs = [
            'Đổi mật khẩu'
        ];
        return view(
            'client.seeker.change-password',
            [
                'breadcrumbs' => $breadcrumbs,
                'title' => 'Đổi mật khẩu',
                'majors' => $this->getmajors(),
            ]
        );
    }
    public function changePasswordSucsses(Request $request)
    {
        try {
            $user = $this->user->where('id', Auth::guard('user')->user()->id)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            $this->setFlash(_('Đổi mật khẩu thành công'));
            return redirect()->route('user.changepass');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setFlash(_('Đã có một lỗi xảy ra'));
            return redirect()->route('user.changepass');
        }
    }
    public function updateTitleCv(Request $request, $id)
    {
        try {
            $cv = $this->upload->where('id', $id)->first();
            $cv->title = $request['title'];
            $cv->save();
            return response()->json([
                'message' => 'Cập nhật thành công',
                'status' => StatusCode::OK,
            ], StatusCode::OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Có một lỗi không xác định đã xảy ra',
                'status' =>  StatusCode::FORBIDDEN,
            ], StatusCode::OK);
        }
    }
    public function deleteCv($id)
    {
        $this->upload->destroy($id);
        $this->setFlash(__('Xóa cv thành công'));
        return redirect()->route('profile.index');
    }
    public function updateAvatar(Request $request)
    {
        try {
            $user = $this->user->where('id', Auth::guard('user')->user()->id)->first();
            if ($request->hasFile('images')) {
                $user->images = $request->images->storeAs('images/cv', $request->images->hashName());
            }
            $user->save();
            $this->setFlash(_('Cập nhật thành công'));
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setFlash(_('Đã có một lỗi xảy ra'));
            return redirect()->back();
        }
    }
    public function getDatalove($id)
    {
        return response()->json([
            'data' => Favourite::where([
                ['job_id', $id],
                ['user_id', Auth::guard('user')->user()->id],
            ])->first()
        ]);
    }
}