<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Items_setting extends CI_Controller{
    function __construct() {
                parent::__construct();
               $this->load->library('posnic');   
    }
    function index(){     
         if(!isset($_SESSION['Uid'])){
                redirect('home');
        }else{
            $this->get_setting();
        }
    }
    function get_setting(){
                $config["base_url"] = base_url()."index.php/items_setting/get_settings";
	        $config["total_rows"] =$this->posnic->posnic_module_count('items'); 
	        $config["per_page"] = 8;
	        $config["uri_segment"] = 3;
	        $this->pagination->initialize($config);	 
	        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;               
                $data['count']=$this->posnic->posnic_module_count('items');                 
	        $data["row"] = $this->posnic->posnic_module_limit_result('items',$config["per_page"], $page);           
	        
                $data["links"] = $this->pagination->create_links();
                $this->load->view('item_list',$data);
    }   
    function edit_item($guid){
                $where=array('guid'=>$guid);
                $data['row']=$this->posnic->posnic_module_result($where,'items');
                $this->load->view('edit_setting',$data);
        
    }
    function update(){
        
        if (!$_SERVER['HTTP_REFERER']){ redirect('home');}
        else{
            if($this->input->post('cancel')){
                redirect('items_setting');
            }
            if($this->input->post('save')){
               $id=  $this->input->post('id');
               $this->load->library('form_validation');
               $this->form_validation->set_rules("min_qty",$this->lang->line('min_qty'),'required|max_length[15]|regex_match[/^[0-9]+$/]|xss_clean');                                             
               $this->form_validation->set_rules("max_qty",$this->lang->line('max_qty'),'required|max_length[15]|regex_match[/^[0-9]+$/]|xss_clean');                           
            if ($this->form_validation->run() !== false ) {       
                    $sale=$this->input->post('sale')?1:0;
                    $salses_return=  $this->input->post('salses_return')?1:0;
                    $purchase=  $this->input->post('purchase')?1:0;
                    $purchase_return=  $this->input->post('purchase_return')?1:0;
                    $allow_negative=  $this->input->post('allow_negative')?1:0;
                    $tax=  $this->input->post('tax');
                    $min=  $this->input->post('min_qty');
                    $max=  $this->input->post('max_qty');
                    $this->load->model('item_setting');
                    $this->item_setting->update($id,$min,$max,$tax,$allow_negative,$purchase_return,$purchase,$salses_return,$sale,$_SESSION['Uid']);
                    redirect('items_setting'); 
            }else{
                
                $this->load->model('item_setting');
         $data['row']=$this->item_setting->get_setting($id);
                $this->load->view('template/header');
                $this->load->view('items_settings/edit_setting',$data);
                $this->load->view('template/footer');
            }
                              
        }
    }
    }
    function items_details(){
       
         if (!$_SERVER['HTTP_REFERER']){ redirect('home');}  else{
             if($this->input->post('BacktoHome')){
                redirect('home');
            }
             if($this->input->post('bulk_edit')){
                  $data['row'] = $this->input->post('mycheck'); 
                            if(!$data==''){   
                            $this->load->view('template/header');
                            $this->load->view('items_settings/bulk_edit_setting',$data);
                            $this->load->view('template/footer');
                            
                            }else{
                                redirect('items_setting');
                            }
             }
        
        }
    }
    function bult_update(){        
        if (!$_SERVER['HTTP_REFERER']){ redirect('home');}
        else{
            if($this->input->post('cancel')){
                redirect('items_setting');
            }
            if($this->input->post('save')){
             $data=$this->input->post('id');
              $this->load->library('form_validation');
               $this->form_validation->set_rules("min_qty",$this->lang->line('min_qty'),'required|max_length[15]|regex_match[/^[0-9]+$/]|xss_clean');                                             
               $this->form_validation->set_rules("max_qty",$this->lang->line('max_qty'),'required|max_length[15]|regex_match[/^[0-9]+$/]|xss_clean');                           
            if ($this->form_validation->run() !== false ) {       
                    $sale=$this->input->post('sale')?1:0;
                    $salses_return=  $this->input->post('salses_return')?1:0;
                    $purchase=  $this->input->post('purchase')?1:0;
                    $purchase_return=  $this->input->post('purchase_return')?1:0;
                    $allow_negative=  $this->input->post('allow_negative')?1:0;
                    $tax=  $this->input->post('tax');
                    $min=  $this->input->post('min_qty');
                    $max=  $this->input->post('max_qty');
                    $this->load->model('item_setting');
                     foreach( $data as $key => $value){ 
                       $this->item_setting->update($value,$min,$max,$tax,$allow_negative,$purchase_return,$purchase,$salses_return,$sale,$_SESSION['Uid']);
                    } 
                redirect('items_setting'); 
            }else{
                            $this->load->view('template/header');
                            $this->load->view('items_settings/bulk_edit_setting',$data);
                            $this->load->view('template/footer');
            }
            }
        }
    }
}
?>
