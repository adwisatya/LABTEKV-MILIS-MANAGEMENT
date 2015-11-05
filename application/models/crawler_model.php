<?php
/**
 * Created by PhpStorm.
 * User: adwisatya
 * Date: 9/15/2015
 * Time: 2:04 AM
 */

class Crawler_Model extends CI_Model{
    private $base_url = "https://six.akademik.itb.ac.id/publik/";
    public function __construct(){
        parent::__construct();
        $this->load->helper('file');
        $this->load->library('simple_html_dom');
    }

    public function list_mahasiswa($link){
        $result = array();
        $mahasiswa = array();
        $rawList = file($link);
        $i = 1;
        foreach($rawList as $row){
            $row = str_replace("\n","",$row);
            if($i == 1){
                $result['fakultas'] = strip_tags($row);
            }else if($i == 2){
                $raw = explode(":",$row);
                $result['prodi'] = strip_tags($raw[1]);
            }else if($i == 3){
                $raw = explode(":",$row);
                $result['semester'] = substr(trim(strip_tags($raw[1])),0,1);
                $result['tahun'] = "20".substr(trim(strip_tags($raw[1])),2,2);
            }else if($i == 5){
                $raw = explode(":",$row);
                $rawKode = explode("/",$raw[1]);
                $result['kode'] = trim(strip_tags($rawKode[0]));
                $result['sks'] = substr(trim(explode(",",$rawKode[1])[1]),0,2);
                $result['mata_kuliah'] = explode(",",$rawKode[1])[0];
            }else if($i == 6){
                $raw = explode(":",$row);
                $rawKode = explode("/",$raw[1]);
                $result['kelas'] = $rawKode[0];
                $result['dosen'] = $rawKode[1];
            }
            if(preg_match("/Peserta/",$row)){
                $raw = explode("=",$row);
                $result['jumlah_peserta'] = $raw[1];
            }
            if(is_numeric(substr($row,0,3))){
                $tmparray = array('nim'=> substr($row,4,8),'nama'=>substr($row,15));
                array_push($mahasiswa,$tmparray);
                //echo $row;
            }
            $i++;
        }
        $result['peserta'] = $mahasiswa;
        return $result;
    }
    public function crawl_list_mk_prodi($kode_prodi,$kode_mk,$kelas){
        $url = $this->base_url."daftarkelas.php?ps=".$kode_prodi."&semester=1&tahun=2015&th_kur=2013";
        $rawData = file_get_html($url);
        $rawResult = "";
        foreach($rawData->find('li') as $element){
            if(strpos($element,$kode_mk)){
                if(strpos($element,$kelas)) {
                    $rawResult = $element;
                }
            }
        }
        if($rawResult) {
            return $this->base_url . $rawResult->find('a')[$kelas * 2 - 2]->href;
        }else{
            throw new Exception("Tidak ditemukan kelas dengan kode $kode_mk");
        }
    }


    // Unused function //
    public function show_dpk($kode_prodi,$tahun){
        $complete_link = "daftarkelas.php?ps=135&semester=1&tahun=".$tahun."&th_kur=2013";
        return $complete_link;
    }
    public function prodi_mk($kode_mk){
        $result = "not found";
        $list_mk  = file("application/files/list.txt");
        foreach($list_mk as $mk){
            if(substr($kode_mk,0,2) == substr($mk,0,2)){
                if(substr($kode_mk,2,1)>4){
                    if(substr($kode_mk,2,1)<7){
                        $strata = 2;
                    }else{
                        $strata = 3;
                    }
                }else{
                    $strata = 1;
                }
                $result = $strata.substr($mk,3,2);
                break;
            }
        }
        return $result;
    }
}