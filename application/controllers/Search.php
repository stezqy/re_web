<?php

/**
 * Created by Steven
 * Date: 2016/1/17
 * Time: 10:18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('authentication');
        $this->load->model('exploit_model','exploit');
    }
    public function index() {
        $input=array('title'=>trim($this->input->get("title")),'vendor'=>trim($this->input->get("vendor")),'module'=>trim($this->input->get("module")));
        $input = preg_replace("/(\s+)|(　+)+/"," ", $input);//替代空格,换行,tab,中文空格
        $input = preg_replace("/(\s+)/", " ", $input);//替换多个空格为一个空格
        $data['input']=$input;
        $perpage=4;
        $offset=$this->uri->segment(3)-1;
        $q=array();
        $data['min_page']=0;
        $data['max_page']=0;
        $data['totalpage']=0;
        $flag=1;
        foreach ($input as $key => $v) {
            if($v!=null&&$v!=""){
                $q[$key]=explode(' ',$v);
                 $flag=0;
            }
        } 
        if($flag==1){
            $this->load->view('search.php',$data);
            return;
        }
        $offset*=$perpage;
        if( $this->session->userdata('last_search')!==$input){
            $this->session->set_userdata('last_search', $input);
            $result=$this->exploit->search($q,$offset,true,$perpage);
            $this->session->set_userdata('count', count($result));
            $data['list']=array_slice($result,$offset,$perpage); 
        }
        else{
            $data['list'] = $this->exploit->search($q,$offset,false,$perpage);
        }
        $data['current_page']=$this->uri->segment(3);
        $totalpage=ceil($this->session->userdata('count')/$perpage);
        $data['totalpage']=$totalpage;
        if($data['current_page']<1||$data['current_page']>$totalpage){
            $data['min_page']=0;
            $data['max_page']=0;
        }
        $left=(ceil($data['current_page']/$perpage)-1)*$perpage+1;
        $data['min_page']=$left;
        $data['max_page']=(($left+$perpage-1)>$totalpage)?$totalpage:($left+$perpage-1);
        $this->load->view('search.php',$data);
    }
    /*
    public function search_hint(){
        $keywords= $this->input->post("keywords");
        $data=$this->exploit->hint($keywords);
        echo("[{'keywords':'".iconv_substr($data[0]['vendor'],0,14,"gbk")."'}]");
       
    }*/
}
