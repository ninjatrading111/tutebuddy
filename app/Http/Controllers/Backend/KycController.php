<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\User;
use App\Models\Kyc;
use Mail;
use App\Mail\SendMail;

class KycController extends Controller
{
    /**
     * Get KYC verifcation
     */
    public function index()
    {
        $count = $this->kycCount();

        return view('backend.users.kyc-index', compact('count'));
    }

    /**
     * Show KYC verfication for user
     * 
     * @param string $userId
     */
    public function show(string $userId)
    {
        $user = User::find($userId);
        return view('backend.users.kyc-show', compact('user'));
    }

    /**
     * Get KYC Verifications By Ajax
     * @param string $kycStatus
     * 
     * @return response
     */
    public function getKyc(string $kycStatus)
    {
        $tableData = [];
        switch($kycStatus) {
            case 'pending':
                $kycs = Kyc::where('status', 0)->get();
                $tableData = $this->getTableData($kycs, 'pending');
            break;

            case 'approved':
                $kycs = Kyc::where('status', 1)->get();
                $tableData = $this->getTableData($kycs, 'approved');
            break;

            case 'rejected':
                $kycs = Kyc::where('status', 2)->get();
                $tableData = $this->getTableData($kycs, 'rejected');
            break;

            case 'unverified':
                $teachers = User::role('admin')->get();
                $unverifiedTeachers = collect();

                foreach($teachers as $teacher) {
                    if(!isset($teacher->kyc)) {
                        $unverifiedTeachers->push($teacher);
                    }
                }

                $tableData = $this->getTableData($unverifiedTeachers, 'unverified');
            break;  
        }

        return response()->json([
            'success' => true,
            'data' => $tableData
        ]);
    }

    /**
     * Set KYC
     * @param string $kycId
     * @param string $status
     */
    public function setKyc(string $kycId, string $status, Request $request)
    {
        $kycObj = Kyc::find($kycId);
        $user   = $kycObj->user;
        $msg    = '';

        if($status == 'approve') {
            $kycObj->status = 1;
            $msg = 'Successfully Approved';

            $emailData = [
                'template_type' => 'Kyc_Approved',
                'mail_data'     => [
                    'receiver_name'  => $user->name
                ]
            ];

            Mail::to($user->email)->send(new SendMail($emailData));
        }

        if($status == 'reject') {
            $kycObj->status = 2;
            $kycObj->content = $request->content;
            $msg = 'Successfully Rejected';

            $emailData = [
                'template_type' => 'Kyc_Rejected',
                'mail_data'     => [
                    'receiver_name'     => $user->name,
                    'kyc_reject_reason' => $request->content
                ]
            ];

            Mail::to($user->email)->send(new SendMail($emailData));
        }

        $kycObj->save();

        return response()->json([
            'success' => true,
            'message' => $msg
        ]);
    }

    /**
     * Get Table data
     * @param collection $data
     * @param string     $type
     * 
     * @return array
     */
    public function getTableData(Collection $data, string $type): array
    {
        $arrStatus = [
            'approved'   => '<label class="badge badge-primary" data-toggle="tooltip" data-original-title="Approved">Approved</label>',
            'pending'    => '<label class="badge badge-accent" data-toggle="tooltip" data-original-title="Pending">Pending</label>',
            'rejected'   => '<label class="badge badge-dark" data-toggle="tooltip" data-original-title="Rejected">Rejected</label>',
            'unverified' => '<label class="badge badge-secondary" data-toggle="tooltip" data-original-title="Unverified">Unverified</label>'
        ];
        $teachers = collect();

        if($type != 'unverified') {
            foreach($data as $kyc) {
                $teachers->push($kyc->user);
            }
        } else {
            $teachers = $data;
        }

        $returnData = [];
        foreach($teachers as $user) {

            if(!$user) {
                continue;
            }
            
            $temp = [];
            $temp['index'] = '';

            if(!empty($user->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar ) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. mb_substr($user->name, 0, 2) . '</span>';
            }

            $temp['name']   = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    '. $avatar .'
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'.
                                                $user->email
                                            .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            $documentTypeStr  = 'N/A';

            if($type != 'unverified') {
                if($user->kyc->document_type == 'government_id') {
                    $documentTypeStr = 'Government ID';
                }

                if($user->kyc->document_type == 'passport') {
                    $documentTypeStr = 'Passport';
                }

                if($user->kyc->document_type == 'drive_license') {
                    $documentTypeStr = 'Driving License';
                }
            }

            $temp['document'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-100 mb-4pt">'. $documentTypeStr .'</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';

            $temp['status']  = $arrStatus[$type];
            $temp['actions'] = '';
            
            if($type != 'unverified') {
                $temp['actions'] = view('backend.buttons.show', ['show_route' => route('admin.kyc.show', $user->id)])->render();
            }

            array_push($returnData, $temp);
        }

        return $returnData;
    }

    /**
     * Get KYC count by status
     * 
     * @return array
     */
    private function kycCount(): array
    {
        $count = [
            'pending'    => 0,
            'approved'   => 0,
            'rejected'   => 0,
            'unverified' => 0
        ];

        $teachers = User::role('admin')->get();

        foreach($teachers as $teacher) {
            if(isset($teacher->kyc) && $teacher->kyc->status == 0) {
                $count['pending']++;
            }

            if(isset($teacher->kyc) && $teacher->kyc->status == 1) {
                $count['approved']++;
            }

            if(isset($teacher->kyc) && $teacher->kyc->status == 2) {
                $count['rejected']++;
            }

            if(!isset($teacher->kyc)) {
                $count['unverified']++;
            }
        }

        return $count;
    }
}
