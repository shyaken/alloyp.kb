<?php
    class Khanhpt extends Controller {

        function Khanhpt() {
            parent::Controller();
        }

        function index() {
            $this->db->select('*');
            $this->db->from('apps');
            $this->db->join('app_version', 'apps.app_id = app_version.app_id');
            //$this->db->limit(10);
            $query = $this->db->get();
            $result = $query->result_array();
            $header = "AppID\tAppName\tLink\n";
            $content = "";
            foreach ($result as $re) {
                $links = explode('@@', $re['link']); 
                $link = join("\t", $links);
                $content .= $re['app_id'] . "\t" . $re['app_name'] . "\t" . $link . "\n";
            }
            $filename = "apps.xls";
            header("Content-type: application/x-msdownload");
            header('Content-Disposition: attachment; filename='.$filename);
            echo "$header$content";
        }
    }
?>
