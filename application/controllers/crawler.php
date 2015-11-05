<?php
/**
 * Created by PhpStorm.
 * User: adwisatya
 * Date: 9/15/2015
 * Time: 2:03 AM
 */

class Crawler extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Crawler_Model');
    }
    public function show_dpk(){
        $prodi_mk= $this->uri->segment('3');
        $kode_mk = $this->uri->segment('4');
        $kelas = $this->uri->segment('5');

        $this->check_parameter(array($prodi_mk,$kode_mk,$kelas));
        try{
            try{
                $link = $this->Crawler_Model->crawl_list_mk_prodi($prodi_mk,$kode_mk,$kelas);
            }catch (Exception $e){
                $error['error'] = "Tidak ditemukan kelas $kelas dengan kode $kode_mk";
                $this->send_error($error,404);
                exit();
            }
            $result = $this->Crawler_Model->list_mahasiswa($link);
            $this->send_success($result);
        }catch (Exception $e){
            $error['error'] = "Terjadi kesalahan pada server";
            $this->send_error($error,500);
        }

    }


    // testing function only //
    public function check_mk(){
        $kode_mk = $this->uri->segment('3');
        echo $this->Crawler_Model->prodi_mk($kode_mk);
    }
    public function crawl_list_mk_prodi(){
        $prodi_mk= $this->uri->segment('3');
        $kode_mk = $this->uri->segment('4');
        $kelas = $this->uri->segment('5');
        echo $this->Crawler_Model->crawl_list_mk_prodi($prodi_mk,$kode_mk,$kelas);
    }
    public function list_mahasiswa(){
        $this->Crawler_Model->list_mahasiswa();
    }
}