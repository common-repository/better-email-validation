<?php

if (!defined('BEV_VERSION') ) exit;

// Define getmxrr function for poor Windows Users :) 
if (!function_exists ('getmxrr') ) {
        function getmxrr($hostname, &$mxhosts, &$mxweight) {
                if (!is_array ($mxhosts) ) { $mxhosts = array (); }

                if (!empty ($hostname) ) {
                        $output = "";
                        @exec ("nslookup.exe -type=MX $hostname.", $output);
                        $imx=-1;

                        foreach ($output as $line) {
                                $imx++;
                                $parts = "";
                                if (preg_match ("/^$hostname\tMX preference = ([0-9]+), mail exchanger = (.*)$/", $line, $parts) ) {
								$mxweight[$imx] = $parts[1];
                                        $mxhosts[$imx] = $parts[2];
                                }
                        }
                        return ($imx!=-1);
                }
                return false;
        }
}

if ( !class_exists('Email_Verifier')) {
	class Email_Verifier {
		public function verify( $email ) {
			// validate email address syntax
			$exp = "/^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$/";
	        
			if ( !preg_match($exp, $email) ) return FALSE;
			
			return $this->deep_email_verification($email);
		}
		
		private function get_mx_records ( $email )	{
			$mailparts = explode("@",$email);
	        $hostname  = $mailparts[1];
	        $mx_record_available = getmxrr( $hostname, $mx_records, $mx_weight );
			if ( $mx_record_available ) {
				$mx_servers=array();
	            for($i=0;$i<count($mx_records);$i++){
	                   $mx_servers[$mx_weight[$i]]=$mx_records[$i];
	            }
	            // sort array mxs to get servers with highest prio
	            ksort ($mx_servers, SORT_NUMERIC ); reset ($mx_servers);
				return $mx_servers;
			}
			return FALSE;
		}
	
		private function get_domain_from_url ( $url ) {
			// get host name from URL
			preg_match("/^(http:\/\/)?([^\/]+)/i", $url , $matches);
			$host = $matches[2];
			// get last two segments of host name
			preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
			if ( $matches ) return $matches[0];
			else return __('wordpress.org'); 
		}
				
		private function deep_email_verification ( $email ) {
			        
	        $email_found_on_server = FALSE;
			$mx_servers = $this->get_mx_records($email);
		
	        if($mx_servers != FALSE ){
	                while (list ($mx_weight, $mx_host) = each ($mx_servers) ) {
	                        if($email_found_on_server == FALSE){
	                                $fp = @fsockopen($mx_host,25, $errno, $errstr, 2);
	                                if( $fp )
	                                {
											$mail_from = get_option('admin_email');
											$mail_from_host = $this->get_domain_from_url(site_url());
				   		                    $ms_resp = $this->send_command($fp, "HELO " . $mail_from_host);
	                                        $ms_resp.= $this->send_command($fp, "MAIL FROM:<". $mail_from .">");
	                                        $rcpt_text = $this->send_command($fp, "RCPT TO:<" . $email . ">");
	                                        $ms_resp.=$rcpt_text;
	
	                                        if(substr( $rcpt_text, 0, 3) == "250")
	                                                $email_found_on_server = TRUE;
	                                        $ms_resp.= $this->send_command($fp, "QUIT");
	                                        fclose($fp);
	                                } 
	                        } else {
	                        	break;
	                        }
	                }
	        }
	        return $email_found_on_server;
		}
	
		private function send_command($fp, $out){
	        fwrite($fp, $out . "\r\n");
	        return $this->get_data($fp);
		}
	
		private function get_data($fp){
		        $s="";
		        stream_set_timeout($fp, 2);
		        for($i=0;$i<2;$i++) $s.=fgets($fp, 1024);
		        return $s;
		}
	} //End class
} //End class_exists check