<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scorm_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_scorm_curriculum_by_course_id($course_id = ""){
		if($course_id > 0){
			$this->db->where('course_id', $course_id);
		}
		return $this->db->get('scorm_curriculum');
	}

	public function remove_curriculum($course_id = ""){
		$this->db->where('course_id', $course_id);
        $this->db->delete('scorm_curriculum');

        //deleted previews course directory
        $scorm_query = $this->get_scorm_curriculum_by_course_id($course_id);
        $this->scorm_model->deleteDir('uploads/scorm/courses/'.$scorm_query->row('identifier'));
	}

	public function add_curriculum($course_id = "") {
		$data['scorm_provider'] = html_escape($this->input->post('scorm_provider'));
		$data['identifier'] = md5(rand(10000, 99999));
		$data['course_id'] = $course_id;
		$data['date_added'] = strtotime(date('d M Y'));

		// Create update directory.
		if (!is_dir('uploads/scorm'))
			mkdir('uploads/scorm', 0777, true);
		if (!is_dir('uploads/scorm/zip'))
			mkdir('uploads/scorm/zip', 0777, true);
		if (!is_dir('uploads/scorm/courses'))
			mkdir('uploads/scorm/courses', 0777, true);

		if($data['scorm_provider'] == 'ispring' || $data['scorm_provider'] == 'articulate' || $data['scorm_provider'] == 'adobe_captivate'){
			if (!empty($_FILES['scorm_zip']['name'])) {
				$path = "uploads/scorm/zip/".$data['identifier'].'.zip';
				move_uploaded_file($_FILES['scorm_zip']['tmp_name'], $path);
				//Unzip uploaded update file and remove zip file.
				$zip = new ZipArchive;
				$res = $zip->open($path);
				$zip->extractTo('uploads/scorm/courses/'.$data['identifier']);
				$zip->close();
				if($path){
					unlink($path);
				}
				$scorm_query = $this->get_scorm_curriculum_by_course_id($course_id);
				if($scorm_query->num_rows() > 0){
					//deleted previews course directory
					$this->deleteDir('uploads/scorm/courses/'.$scorm_query->row('identifier'));

					$this->db->where('course_id', $course_id);
					$this->db->update('scorm_curriculum', $data);
					$this->session->set_flashdata('flash_message', get_phrase('scorm_course_uploaded_successfully'));
					return 'success';
				}else{
					$this->db->insert('scorm_curriculum', $data);
					$this->session->set_flashdata('flash_message', get_phrase('scorm_course_uploaded_successfully'));
					return 'success';
				}
			}else{
				return get_phrase('please_choose_a_scorm_zip_file');
			}
		}else{
			return get_phrase('please_select_a_course_provider');
		}
	}

	public static function deleteDir($dirPath) {
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}

}