<?php
/*
 * Copyright 2016 MasterCard International.
 *
 * Redistribution and use in source and binary forms, with or without modification, are 
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list of 
 * conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * Neither the name of the MasterCard International Incorporated nor the names of its 
 * contributors may be used to endorse or promote products derived from this software 
 * without specific prior written permission.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES 
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT 
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING 
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF 
 * SUCH DAMAGE.
 *
 */

namespace MasterCard\Api\Repower;

use MasterCard\Core\Model\RequestMap;
use MasterCard\Core\ApiConfig;
use MasterCard\Core\Security\OAuth\OAuthAuthentication;



class AllTest extends \PHPUnit_Framework_TestCase {

    protected function setUp() {
        ApiConfig::setDebug(true);
        ApiConfig::setSandbox(true);
        $privateKey = file_get_contents(getcwd()."/mcapi_sandbox_key.p12");
        ApiConfig::setAuthentication(new OAuthAuthentication("L5BsiPgaF-O3qA36znUATgQXwJB6MRoMSdhjd7wt50c97279!50596e52466e3966546d434b7354584c4975693238513d3d", $privateKey, "alias", "password"));
    }

    
    
                
        public function test_a_test_1()
        {
            $map = new RequestMap();
            $map->set ("RepowerRequest.ICA", "009674");
            $map->set ("RepowerRequest.ProcessorId", "9000000442");
            $map->set ("RepowerRequest.RoutingAndTransitNumber", "990442082");
            $map->set ("RepowerRequest.CardAcceptor.Name", "Prepaid Card");
            $map->set ("RepowerRequest.CardAcceptor.City", "St Charles");
            $map->set ("RepowerRequest.CardAcceptor.State", "MO");
            $map->set ("RepowerRequest.CardAcceptor.Country", "USA");
            $map->set ("RepowerRequest.CardAcceptor.PostalCode", "63301");
            $map->set ("RepowerRequest.MerchantType", "6532");
            $map->set ("RepowerRequest.Channel", "W");
            $map->set ("RepowerRequest.LocalTime", "092435");
            $map->set ("RepowerRequest.LocalDate", "1230");
            $map->set ("RepowerRequest.TransactionReference", "2304237756918437868");
            $map->set ("RepowerRequest.CardNumber", "5184680430000014");
            $map->set ("RepowerRequest.TransactionAmount.Value", "5000");
            $map->set ("RepowerRequest.TransactionAmount.Currency", "840");
            
            $response = RePowerTransfer::create($map);
            $this->customAssertValue("REPOWERPAYMENT", $response->get("Repower.TransactionHistory.Transaction.Type"));
            $this->customAssertValue("00", $response->get("Repower.TransactionHistory.Transaction.Response.Code"));
            $this->customAssertValue("Approved or completed successfully", $response->get("Repower.TransactionHistory.Transaction.Response.Description"));
            $this->customAssertValue("15000", $response->get("Repower.AccountBalance.Value"));
            $this->customAssertValue("840", $response->get("Repower.AccountBalance.Currency"));
            
        }
        
    
    
    
    
    
    
    
    
                
        public function test_a_test_2()
        {
            $map = new RequestMap();
            $map->set ("RepowerReversalRequest.ICA", "009674");
            $map->set ("RepowerReversalRequest.TransactionReference", "2304237756918437868");
            $map->set ("RepowerReversalRequest.ReversalReason", "Incorrect amount");
            
            $response = RePowerTransferReversal::create($map);
            $this->customAssertValue("2304237756918437868", $response->get("RepowerReversal.TransactionReference"));
            $this->customAssertValue("REPOWERPAYMENTREVERSAL", $response->get("RepowerReversal.TransactionHistory.Transaction.Type"));
            $this->customAssertValue("00", $response->get("RepowerReversal.TransactionHistory.Transaction.Response.Code"));
            $this->customAssertValue("Approved or completed successfully", $response->get("RepowerReversal.TransactionHistory.Transaction.Response.Description"));
            
        }
        
    
    
    
    
    
    
    

    protected function customAssertValue($expected, $actual) {
        if (is_bool($actual)) {
            $this->assertEquals(boolval($expected), $actual);
        } else if (is_float($actual)) {
            $this->assertEquals(floatval($expected), $actual);
        } else {
            $this->assertEquals(strtolower($expected), strtolower($actual));
        }
    }
}



