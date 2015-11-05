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
        $this->log_time('list_mahasiswa',0);
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
                $rawKode = explode(" / ",$raw[1]);
                $result['kelas'] = $rawKode[0];
                $result['dosen'] = $rawKode[1];
            }
            if(preg_match("/Peserta/",$row)){
                $raw = explode("=",$row);
                $result['jumlah_peserta'] = $raw[1];
            }
            if(is_numeric(substr($row,0,3))){
                //$tmparray = array('nim'=> substr($row,4,8),'nama'=>substr($row,15));
                //array_push($mahasiswa,$tmparray);
                //echo $row;
                array_push($mahasiswa, substr($row,4,8));
            }
            $i++;
        }
        $result['peserta'] = $mahasiswa;
        //return $result;
        $this->log_time('list_mahasiswa',1);
        return $this->dump_to_file($result['tahun'],$result['semester'],$result['kode'],$result['dosen'],$result['peserta']);
    }
    public function dosen_to_email($nama){
        $this->log_time('dosen_to_email',0);
        $database_dosen = array("Santika Wachyudin P."=>"","Bugi Wibowo"=>"bugi@stei.itb.ac.id","Kusprasapta Mutijarsa"=>"soni@stei.itb.ac.id","Yudi Satria Gondokaryono"=>"ygondokaryono@stei.itb.ac.id","Tricya E. Widagdo"=>"cia@stei.itb.ac.id","Christine Suryadi"=>"christine@stei.itb.ac.id","Saiful Akbar"=>"saiful@stei.itb.ac.id","Samsudin"=>"samsudin@informatika.org","Santika Wachjudin"=>"santika@informatika.org","Saswinadi Sasmojo"=>"saswinadi@informatika.org","Setiadi Yazid"=>"setiadi@informatika.org","Sudiarto"=>"sudiarto@informatika.org","Yani Widyani"=>"yani@stei.itb.ac.id","Yudistira Dwi Wardhana Asnar"=>"yudis@informatika.org","Riza Satria Perdana"=>"riza@stei.itb.ac.id","Adi Mulyanto"=>"adi.m@stei.itb.ac.id","Fazat Nur Azizah"=>"fazat@stei.itb.ac.id","G.A.Putri Saptawati"=>"putri.saptawati@stei.itb.ac.id","Reza Ferdiansyah"=>"reza@informatika.org","Rila Mandala"=>"rila@informatika.org","Nur Ulfa Maulidevi"=>"ulfa@stei.itb.ac.id","Wikan Danar"=>"wikan@informatika.org","Harlili S."=>"harlili@stei.itb.ac.id","Ayu Purwarianti"=>"ayu@stei.itb.ac.id","Muhammad Rian"=>"rian@informatika.org","Riza Satria Perdana"=>"riza@stei.itb.ac.id","Achmad Imam Kistijantoro"=>"imam@stei.itb.ac.id","Masayu Leylia Khodra"=>"masayu@stei.itb.ac.id","Munawar Ahmad ZA"=>"munawar@informatika.org","Oerip Santoso"=>"oerip@informatika.org","Peb Ruswo Aryan"=>"peb@informatika.org","Petra Barus"=>"petra@alumni.informatika.org","Henny Yusnita Zubir"=>"henny@stei.itb.ac.id","Rinaldi"=>"rinaldi-m@stei.itb.ac.id","Hira Laksmiwati S."=>"hira@stei.itb.ac.id","Dwi Hendratmo W."=>"dwi@stei.itb.ac.id","Benhard Sitohang"=>"benhard@stei.itb.ac.id\nbenhard@poss.itb.ac.id","M.Sukrisno Mardiyanto"=>"sukrisno@stei.itb.ac.id","Iping Supriana"=>"iping@stei.itb.ac.id","Judhi Santoso"=>"judhi@stei.itb.ac.id","Kusnadi"=>"kusnadi@informatika.org","Afwarman"=>"afwarman@stei.itb.ac.id\nawang@informatika.org","Arry Akhmad Arman"=>"arman@stei.itb.ac.id","Oerip S. Santoso"=>"","Mary Handoko Wijoyo"=>"mary@stei.itb.ac.id","Mewati Ayub"=>"mewati@informatika.org","Armein Z.R Langi"=>"armein.z.r.langi@stei.itb.ac.id","Husni S. Sastramihardja"=>"husni@stei.itb.ac.id","Achmad Imam"=>"imam@informatika.org","Indriani NH"=>"indriani@informatika.org","Inggriani Liem"=>"inge@informatika.org","Budi Rahardjo"=>"budi@stei.itb.ac.id","Kridanto Surendro"=>"endro@informatika.org","Windy Gambetta"=>"windy@informatika.org\windy@stei.itb.ac.id","Suhono Harso Supangkat"=>"suhono@stei.itb.ac.id","Yusep Rosmansyah"=>"yusep@stei.itb.ac.id","Suhardi"=>"suhardi@stei.itb.ac.id","Bambang Riyanto Trilaksono"=>"briyanto@lskk.ee.itb.ac.id","Jaka Sembiring"=>"jaka@itb.ac.id","Yoanes Bandung"=>"","Albarda"=>"albar@stei.itb.ac.id","Bambang Pharmasetiawan,M"=>"pharma@stei.itb.ac.id","Bayu Hendradjaya"=>"bayu@stei.itb.ac.id","Wikan Danar Sunindyo"=>"","Yudistira Dwi Wardhana"=>"","Dessi Puji Lestari"=>"","Herman"=>"","Ferry Mukharradi"=>"","M.Ikbal Arifyanto"=>"","Muhammad Irfan Hakim"=>"","Rinovia Mery Garnierita S."=>"","Ganda Marihot Simangunsong"=>"","Zuher Syihab"=>"","Fatkhan"=>"","Warsa"=>"","I Made Astina"=>"","Sri Raharno"=>"","Muhammad Kusni"=>"","FX. Sangriyadi Setio"=>"","Heru Wibowo Poerbo"=>"","Aswin Indraprastha"=>"","Ibnu Syabri"=>"","Saut Aritua Hasiholan Sagala"=>"","Alvanov Zpalanzani"=>"","Bismo Jelantik Joyodiharjo"=>"","Hafiz Aziz Ahmad"=>"","Mursyid Hasan Basri"=>"","Admin Informatika"=>"admin@informatika.org","Bambang Apriono"=>"apriono@informatika.org","Benny Boelhasrin"=>"benny@informatika.org","Cece Hidayat"=>"cece@informatika.org","Tricya Widagdo"=>"cia@informatika.org","Dessi Puji"=>"dessipuji@informatika.org","Dicky Prima Satya"=>"dicky@informatika.org","Didin Sjahril"=>"didin@informatika.org","Kridanto Surendo"=>"endro@informatika.org","Fajar Juang"=>"fajar@informatika.org");
        if(!$database_dosen[$nama]){
            $this->log_time('dosen_to_email',1);
            return "";
        }else{
            $this->log_time('dosen_to_email',1);
            return $database_dosen[$nama];
        }
    }
    public function dump_to_file($tahun,$semester,$kode_kuliah,$dosen,$list_mahasiswa){
        $this->log_time('dump_to_file',0);
        try{
            $file = "application/files/".$tahun."-".$semester."-".$kode_kuliah.".txt";
            if(fopen($file,"r")){
                unlink($file);
            }
            $wfile = fopen($file,"a");
            fwrite($wfile,$this->dosen_to_email($dosen)."\n");
            foreach($list_mahasiswa as $mahasiswa){
                fwrite($wfile, $mahasiswa."@std.stei.itb.ac.id\n");
            }
            fclose($wfile);
            $this->log_time('dump_to_file',1);
            return true;
        }catch(Exception  $e){
            return false;
        }
    }
    public function crawl_list_mk_prodi($kode_prodi,$kode_mk,$kelas){
        $this->log_time('craw_list_mk_prodi',0);
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
            $this->log_time('crawl_list_mk_prodi',1);
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
    public function log_time($action,$type){
        switch ($type) {
            case 0:
                echo $action." start from ".date("Y-m-d H:i:s")."\n";
                break;
            case 1:
                echo $action." end at ".date("Y-m-d H:i:s")."\n";
                break;
        }
    }
}