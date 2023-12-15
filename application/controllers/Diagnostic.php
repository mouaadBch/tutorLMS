<?php
/* code Mouaad */
defined('BASEPATH') or exit('No direct script access allowed');

class Diagnostic extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        // $this->load->library('stripe');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        // CHECK CUSTOM SESSION DATA
        $this->user_model->check_session_data();

        //If user was deleted
        if ($this->session->userdata('user_login') && $this->user_model->get_all_user($this->session->userdata('user_id'))->num_rows() == 0) {
            $this->user_model->session_destroy();
        }

        if (!$this->session->userdata('user_id')) {
            redirect(site_url('home'), 'refresh');
        }

        ini_set('memory_limit', '1024M');
    }
    public function index()
    {
        $page_data['page_name'] = "diagnostic";
        $page_data['page_title'] = site_phrase('Diagnostic');
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }


    public function course($idCours)
    {
        $my_courses = $this->db->get_where('enrol', array('user_id' => $this->session->userdata('user_id'), 'course_id' => $idCours))->row_array();
        if (!($my_courses)) {
            redirect(site_url('home'), 'refresh');
        }

        $data = [];
        //cours info
        $course_details = $this->crud_model->get_course_by_id($my_courses['course_id'])->row_array();
        $data['cours_title'] = $course_details['title'];
        $data['idCours'] = $idCours;
        $data['link_offre'] = $course_details['link_offre'];
        $data['cours_short_description'] = $course_details['short_description'];
        $data['cours_language'] = $course_details['language'];
        $data['cours_level'] = $course_details['level'];
        //section info
        $sections = $this->crud_model->get_section('course', $my_courses['course_id'])->result_array();
        $sectionOrder = 0;
        foreach ($sections as $section) {
            $sectionOrder++;
            $data['section'][$section['id']] = [
                'id' => $section['id'],
                'sectionOrder' => $sectionOrder,
                'title' => $section['title'],
            ];
            $quizzes = $this->crud_model->get_quiz_with_section($section['id'])->result_array();
            foreach ($quizzes as $quiz) {
                $data['section'][$section['id']]['quiz'][$quiz['id']] = [
                    'id' => $quiz['id'],
                    'title' => $quiz['title'],
                ];
                $questions = $this->crud_model->get_quiz_questions($quiz['id'])->result_array();
                foreach ($questions as $question) {
                    $data['section'][$section['id']]['quiz'][$quiz['id']]['question'][$question['id']] = [
                        'id' => $question['id'],
                        'title' => $question['title'],
                        'type' => $question['type'],
                        'options' => $question['options'],
                        'correct_answers' => $question['correct_answers'],
                    ];
                }
                $quiz_results = $this->db->order_by('date_added', 'desc')
                    ->get_where('quiz_results', array('quiz_id' => $quiz['id'], 'is_submitted' => 1, 'user_id' => $this->session->userdata('user_id')))->result_array();
                foreach ($quiz_results as $quiz_result) {
                    $data['section'][$section['id']]['quiz'][$quiz['id']]['quiz_result'][] = [
                        'quiz_id' => $quiz_result['quiz_id'],
                        'quiz_result_id' => $quiz_result['quiz_result_id'],
                        'user_answers' => $quiz_result['user_answers'],
                        'correct_answers' => $quiz_result['correct_answers'],
                        'total_obtained_marks' => $quiz_result['total_obtained_marks'],
                        'date_updated' => $quiz_result['date_updated'],
                    ];
                }
            }
        }

        $quiz_result_section = [];

        foreach ($data['section'] as $section) {
            $nb_quiz = 0;
            $nb_correct = 0;

            foreach ($section['quiz'] as $quiz) {
                $nb_quiz += count($quiz['question']);
                $last_quiz_result = isset($quiz['quiz_result'][0]) ? $quiz['quiz_result'][0] : null;
                if ($last_quiz_result !== null) {
                    $correctAnswers = json_decode($last_quiz_result['correct_answers'], true);
                    $nb_correct += count($correctAnswers);
                }
            }


            $pourcentageParSection = ($nb_correct / $nb_quiz) * 100;
            $colorpourcentageParSection = '';
            if ($pourcentageParSection >= 75) {
                $colorpourcentageParSection = 'green';
            } elseif ($pourcentageParSection >= 50) {
                $colorpourcentageParSection = 'orange';
            } else {
                $colorpourcentageParSection = 'red';
            }
            $quiz_result_section[$section['id']] = [
                'id' => $section['id'],
                'title' => $section['title'],
                'color' => $colorpourcentageParSection,
                'pourcentage' => $pourcentageParSection,
                'nb_quiz' => $nb_quiz,
                'nb_correct' => $nb_correct,
            ];



            /*$quiz_result_section[$section['id']] = [
                'id' => $section['id'],
                //'title' => $section['title'],
                'title' => 'Section' . $section['sectionOrder'],
                'pourcentage' => $pourcentageParSection,
                'color' => $colorpourcentageParSection,
                'nb_quiz' => $nb_quiz,
                'nb_false' => $nb_quiz - $nb_correct,
                'nb_correct' => $nb_correct,
            ]; */
        }



        $page_data['page_name'] = "diagnostic";
        $page_data['page_title'] = site_phrase('Diagnostic');
        $page_data['typeDiagnostic'] = 'cours';
        $page_data['data'] = $data;
        $page_data['quiz_result_section'] = $quiz_result_section;
        #$page_data['testData'] = $testData;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }
}
