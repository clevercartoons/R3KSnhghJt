<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Onlineexam.php
 * @copyright : Reserved RamomCoder Team
 */

class Onlineexam extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('onlineexam_model');
        $this->load->model('subject_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
                'js/online-exam.js',
            ),
        );
    }

    /* online exam controller */
    public function index()
    {
        // check access permission
        if (!get_permission('online_exam', 'is_view')) {
            access_denied();
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['examList'] = $this->onlineexam_model->examList();
        $this->data['title'] = translate('online_exam');
        $this->data['sub_page'] = 'onlineexam/index';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    /* online exam table list controller */
    public function getExamListDT()
    {
        if ($_POST) {
            $postData = $this->input->post();
            $currencySymbol = $this->data['global_config']['currency_symbol'];
            echo $this->onlineexam_model->examListDT($postData, $currencySymbol);
        }
    }

    /* online exam edit controller */
    public function edit($id = '')
    {
        // check access permission
        if (!get_permission('online_exam', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->exam_validation();
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $this->onlineexam_model->saveExam($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('onlineexam');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['onlineexam'] = $this->app_lib->getTable('online_exam', array('t.id' => $id), true);
        $this->data['title'] = translate('online_exam');
        $this->data['sub_page'] = 'onlineexam/edit';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    protected function exam_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $examType = $this->input->post('exam_type');
        $this->form_validation->set_rules('title', translate('title'), 'trim|required');
        $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
        $this->form_validation->set_rules('section[]', translate('section'), 'trim|required');
        $this->form_validation->set_rules('subject[]', translate('subject'), 'trim|required');
        $this->form_validation->set_rules('start_date', translate('start_date'), 'trim|required');
        $this->form_validation->set_rules('end_date', translate('end_date'), 'trim|required');
        $this->form_validation->set_rules('start_time', translate('start_time'), 'trim|required');
        $this->form_validation->set_rules('end_time', translate('end_time'), 'trim|required');
        $this->form_validation->set_rules('duration', translate('duration'), 'trim|required');
        $this->form_validation->set_rules('participation_limit', translate('limits_of_participation'), 'trim|required|numeric');
        $this->form_validation->set_rules('mark_type', translate('mark_type'), 'trim|required');
        $this->form_validation->set_rules('passing_mark', translate('passing_mark'), 'trim|required|numeric');
        $this->form_validation->set_rules('instruction', translate('instruction'), 'trim|required');
        $this->form_validation->set_rules('question_type', translate('question_type'), 'trim|required');
        $this->form_validation->set_rules('publish_result', translate('result_publish'), 'trim|required');
        if (!empty($examType)) {
            $this->form_validation->set_rules('exam_fee', translate('exam_fee'), 'trim|required|numeric');
        }
    }

    /* online exam save in DB controller */
    public function exam_save()
    {
        if ($_POST) {
            $this->exam_validation();
            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $this->onlineexam_model->saveExam($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    /* online exam delete in DB controller */
    public function delete($id = '')
    {
        if (get_permission('online_exam', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('online_exam');
        }
    }

    /* Online exam question controller */
    public function question()
    {
        if (!get_permission('question_bank', 'is_view')) {
            access_denied();
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('question');
        $this->data['sub_page'] = 'onlineexam/question';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    public function getQuestionListDT()
    {
        if ($_POST) {
            $postData = $this->input->post();
            echo $this->onlineexam_model->questionListDT($postData);
        }
    }

    public function question_add()
    {
        if (!get_permission('question_bank', 'is_add')) {
            access_denied();
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('question');
        $this->data['sub_page'] = 'onlineexam/question_add';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    public function question_edit($id = '')
    {
        if (!get_permission('question_bank', 'is_edit')) {
            access_denied();
        }
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['questions'] = $this->app_lib->getTable('questions', array('t.id' => $id), true);
        $this->data['title'] = translate('question_edit');
        $this->data['sub_page'] = 'onlineexam/question_edit';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    public function question_edit_save($id = '')
    {
        if (!get_permission('question_bank', 'is_edit')) {
            ajax_access_denied();
        }
        if ($_POST) {
            $this->question_validation();
            if ($this->form_validation->run() == true) {
                $this->onlineexam_model->saveQuestions();
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('onlineexam/question');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
    }

    protected function question_validation()
    {
        $questionType = $this->input->post('question_type');
        $this->form_validation->set_rules('question_level', translate('question_level'), 'trim|required');
        $this->form_validation->set_rules('mark', translate('mark'), 'trim|required|numeric');
        $this->form_validation->set_rules('question', translate('question'), 'trim|required');
        if ($questionType == 1) {
            $this->form_validation->set_rules('option1', translate('option') . " " . 1, 'trim|required');
            $this->form_validation->set_rules('option2', translate('option') . " " . 2, 'trim|required');
            $this->form_validation->set_rules('answer', translate('answer'), 'trim|required');
        }
        if ($questionType == 2) {
            $this->form_validation->set_rules('option1', translate('option') . " " . 1, 'trim|required');
            $this->form_validation->set_rules('option2', translate('option') . " " . 2, 'trim|required');
            $this->form_validation->set_rules('option3', translate('option') . " " . 3, 'trim|required');
            $this->form_validation->set_rules('option4', translate('option') . " " . 4, 'trim|required');
            $this->form_validation->set_rules('answer[]', translate('answer'), 'trim|required');
        }
        if ($questionType == 3 || $questionType == 4) {
            $this->form_validation->set_rules('answer', translate('answer'), 'trim|required');
        }
    }

    public function question_save()
    {
        if (!get_permission('question_bank', 'is_add')) {
            ajax_access_denied();
        }
        $this->question_validation();
        if ($this->form_validation->run() == true) {
            $this->onlineexam_model->saveQuestions();
            $message = translate('information_has_been_saved_successfully');
            $array = array('status' => 'success', 'message' => $message);
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function getQuestion()
    {
        $id = $this->input->post('id');
        $this->data['questions'] = $this->onlineexam_model->get('questions', array('id' => $id), true);
        $this->load->view('onlineexam/question_view', $this->data);
    }

    public function question_delete($id = '')
    {
        if (get_permission('question_bank', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('questions');
        }
    }

    public function manage_question($examid = '')
    {
        if (!get_permission('add_questions', 'is_add')) {
            access_denied();
        }
        $this->data['questionType'] = $this->input->post('question_type');
        $this->data['questionLevel'] = $this->input->post('question_level');
        $this->data['classID'] = $this->input->post('class_id');
        $this->data['sectionID'] = $this->input->post('section_id');
        $this->data['subjectID'] = $this->input->post('subject_id');
        $exam = $this->onlineexam_model->get('online_exam', array('id' => $examid), true);
        $this->data['exam'] = $exam;
        $this->data['title'] = translate('manage') . " " . translate('question');
        $this->data['sub_page'] = 'onlineexam/manage_question';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    public function getQuestionDT()
    {
        if ($_POST) {
            $postData = $this->input->post();
            echo $this->onlineexam_model->questionList($postData);
        }
    }

    public function question_assign()
    {
        if (!get_permission('add_questions', 'is_add')) {
            ajax_access_denied();
        }
        if ($_POST) {
            $inputQuestions = $this->input->post('question');
            $examID = $this->input->post('exam_id');
            $negMark = $this->db->select('neg_mark')->where('id', $examID)->get('online_exam')->row()->neg_mark;
            foreach ($inputQuestions as $key => $value) {
                $this->form_validation->set_rules("question[$key][marks]", translate('marks'), 'trim|required|numeric');
                if ($negMark == 1) {
                    $this->form_validation->set_rules("question[$key][negative_marks]", translate('negative_marks'), 'trim|required|numeric');
                } 
            }
            if ($this->form_validation->run() == true) {
                $questionsID = array();
                $cb_questionsID = array();
                $insertData = array();
                foreach ($inputQuestions as $key => $value) {
                    $questionsID[] = $value['id'];
                    if (isset($value['cb_id'])) {
                        $questionID = $value['cb_id'];
                        $cb_questionsID[] = $questionID;

                        $this->db->where(['question_id' => $questionID, 'onlineexam_id' => $examID]);
                        $query = $this->db->get('questions_manage');
                        $result = $query->num_rows();
                        if ($result > 0) {
                            $updateData = array(
                                'marks' => $value['marks'],
                                'neg_marks' => (empty($value['negative_marks']) ? 0 : $value['negative_marks']),
                            );
                            $this->db->where('id', $query->row()->id);
                            $this->db->update('questions_manage', $updateData);
                        } else {
                            $insertData[] = array(
                                'question_id' => $questionID,
                                'onlineexam_id' => $examID,
                                'marks' => $value['marks'],
                                'neg_marks' => (empty($value['negative_marks']) ? 0 : $value['negative_marks']),
                            );
                        }
                    }
                }
                if (!empty($insertData)) {
                    $this->db->insert_batch('questions_manage', $insertData);
                }
                $result = array_diff($questionsID, $cb_questionsID);
                if (!empty($result)) {
                    $this->db->where('onlineexam_id', $examID);
                    $this->db->where_in('question_id', $result);
                    $this->db->delete('questions_manage');
                }
                $array = array('status' => 'success', 'message' => translate('information_has_been_saved_successfully'));
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    // add new question group
    public function question_group()
    {
        if (!get_permission('question_group', 'is_view')) {
            access_denied();
        }
        if (isset($_POST['group'])) {
            if (!get_permission('question_group', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('group_name', translate('group') . " " . translate('name'), 'trim|required|callback_unique_group');
            if ($this->form_validation->run() !== false) {
                $arrayData = array(
                    'name' => $this->input->post('group_name'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $this->db->insert('question_group', $arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('onlineexam/question_group'));
            }
        }
        $this->data['title'] = translate('question') . " " . translate('group');
        $this->data['sub_page'] = 'onlineexam/question_group';
        $this->data['main_menu'] = 'onlineexam';
        $this->load->view('layout/index', $this->data);
    }

    // update existing question group
    public function group_edit()
    {
        if (!get_permission('question_group', 'is_edit')) {
            ajax_access_denied();
        }
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('group_name', translate('group') . " " . translate('name'), 'trim|required|callback_unique_group');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('group_id');
            $arrayData = array(
                'name' => $this->input->post('group_name'),
                'branch_id' => $this->application_model->get_branch_id(),
            );
            $this->db->where('id', $category_id);
            $this->db->update('question_group', $arrayData);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    // delete question group from database
    public function group_delete($id)
    {
        if (get_permission('question_group', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('question_group');
        }
    }

    // question group details send by ajax
    public function groupDetails()
    {
        if (get_permission('question_group', 'is_edit')) {
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $query = $this->db->get('question_group');
            $result = $query->row_array();
            echo json_encode($result);
        }
    }

    /* validate here, if the check unique group name */
    public function unique_group($name)
    {
        $branchID = $this->application_model->get_branch_id();
        $group_id = $this->input->post('group_id');
        if (!empty($group_id)) {
            $this->db->where_not_in('id', $group_id);
        }
        $this->db->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $this->db->get('question_group')->num_rows();
        if ($uniform_row == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_group", translate('already_taken'));
            return false;
        }
    }

    public function exam_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if ($status == 'true') {
            $arrayData['publish_status'] = 1;
        } else {
            $arrayData['publish_status'] = 0;
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $this->db->update('online_exam', $arrayData);
        $return = array('msg' => translate('information_has_been_updated_successfully'), 'status' => true);
        echo json_encode($return);
    }

    public function make_result_publish($id = '')
    {
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $this->db->update('online_exam', ['publish_result' => 1]);
    }

    // get subject list based on class
    public function getByClass()
    {
        $html = '';
        $classID = $this->input->post('classID');
        if (!empty($classID)) {
            $query = $this->onlineexam_model->getSubjectByClass($classID);
            if ($query->num_rows() > 0) {
                $subjects = $query->result_array();
                foreach ($subjects as $row) {
                    $html .= '<option value="' . $row['subject_id'] . '">' . $row['subjectname'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select') . '</option>';
        }
        echo $html;
    }

    public function getExamByClass()
    {
        $html = '';
        $classID = $this->input->post('class_id');
        if (!empty($classID)) {
            $this->db->where('class_id', $classID);
            $this->db->where('session_id', get_session_id());
            $query = $this->db->get('online_exam');
            if ($query->num_rows() > 0) {
                $subjects = $query->result();
                $html .= '<option value="">' . translate('select') . '</option>';
                foreach ($subjects as $row) {
                    $html .= '<option value="' . $row->id . '">' . $row->title . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select') . '</option>';
        }
        echo $html;
    }

    public function result()
    {
        // check access permission
        if (!get_permission('exam_result', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $examID = $this->input->post('exam_id');
            $this->data['result'] = $this->onlineexam_model->examReport($examID, $classID, $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('online_exam') . " " . translate('result');
        $this->data['main_menu'] = 'onlineexam';
        $this->data['sub_page'] = 'onlineexam/result';
        $this->load->view('layout/index', $this->data);
    }

    public function getStudent_result()
    {
        if (get_permission('exam_result', 'is_view')) {
            if ($_POST) {
                $examID = $this->input->post('examID');
                $studentID = $this->input->post('studentID');
                $exam = $this->onlineexam_model->getExamDetails($examID);
                $data['exam'] = $exam;
                $data['studentID'] = $studentID;
                echo $this->load->view('onlineexam/student_result', $data, true);
            }
        }
    }
}
