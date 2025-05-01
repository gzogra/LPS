<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit();
}else{
    $user_account = $_SESSION['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <title>LPS-PromptScope Framework</title>

    <style>
        /* CSS for the spinning loader */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            margin: 10px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* CSS for the two-column layout */
        .two-columns {
            display: flex;
        }

        .column {
            flex: 1;
            padding: 10px;
        }
        
        #responseTextAreaParsed, #edit {
            /*border: 1px solid #ccc;*/
            padding: 10px;
            margin-top: 10px;
        }
        /* arrows */
        .arrow {
          border: solid black;
          border-width: 0 3px 3px 0;
          display: inline-block;
          padding: 3px;
        }
        
        .right {
          transform: rotate(-45deg);
          -webkit-transform: rotate(-45deg);
        }
        
        .left {
          transform: rotate(135deg);
          -webkit-transform: rotate(135deg);
        }
        
        .up {
          transform: rotate(-135deg);
          -webkit-transform: rotate(-135deg);
        }
        
        .down {
          transform: rotate(45deg);
          -webkit-transform: rotate(45deg);
        }

    /* Style the tab */
    .tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }
    
    /* Style the buttons inside the tab */
    .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }
    
    /* Change background color of buttons on hover */
    .tab button:hover {
    
    /*background: #1e5799; */
    /*background: -moz-linear-gradient(-45deg,  #1e5799 0%, #2989d8 50%, #207cca 79%, #7db9e8 100%); */
    /*background: -webkit-linear-gradient(-45deg,  #1e5799 0%,#2989d8 50%,#207cca 79%,#7db9e8 100%);*/
    /*background: linear-gradient(135deg,  #1e5799 0%,#2989d8 50%,#207cca 79%,#7db9e8 100%);*/
    /*filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#7db9e8',GradientType=1 ); */
    
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#e6f0a3+0,d2e638+50,c3d825+51,dbf043+100;Green+Gloss+%232 */
    background: linear-gradient(to bottom,  #e6f0a3 0%,#d2e638 50%,#c3d825 51%,#dbf043 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */

    color:black !important;
    font-weight:bold;
    }
    
    /* Create an active/current tablink class */
    .tab button.active {
      background: #1e5799; /* Old browsers */
    background: -moz-linear-gradient(-45deg,  #1e5799 0%, #2989d8 50%, #207cca 79%, #7db9e8 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(-45deg,  #1e5799 0%,#2989d8 50%,#207cca 79%,#7db9e8 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(135deg,  #1e5799 0%,#2989d8 50%,#207cca 79%,#7db9e8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#7db9e8',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
    
    color:white;
    font-weight:bold;
    }
    
    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px;
      /*border: 1px solid #ccc;*/
      border-top: none;
    }
    
    .custom-control-label{
        font-size:0.8rem;
    }
    
    .labelinfo{
        color:gray;
    
    }
    .header_inst{ font-weight:bold; color:blue;}
    .dif_text{
        color:gray;
        font-size:0.8rem;
    }
    
    .model_response_json{
        display:none;
    }
    
    .initiated_hidden{
        display:none;
    }
    
    a:hover {
	color: white;
	text-decoration: none;
}

/*  MODAL SETTINGS */
    
    .fade:not(.show) {
        opacity: 1;
    }
    
    .modal {
          display: none;
          vertical-align: middle;
          position: relative;
          z-index: 2;
          max-width: 500px;
          box-sizing: border-box;
          width: 90%;
          background: #fff;
          padding: 15px 30px;
          -webkit-border-radius: 8px;
          -moz-border-radius: 8px;
          -o-border-radius: 8px;
          -ms-border-radius: 8px;
          border-radius: 8px;
          -webkit-box-shadow: 0 0 10px #000;
          -moz-box-shadow: 0 0 10px #000;
          -o-box-shadow: 0 0 10px #000;
          -ms-box-shadow: 0 0 10px #000;
          box-shadow: 0 0 10px #000;
          text-align: left;
          height: 300px;
          top:-80px;
        }
        
    .modal-header {
          display: -ms-flexbox;
          -ms-flex-align: start;
          align-items: flex-start;
          -ms-flex-pack: justify;
          justify-content: space-between;
          padding: 1rem 1rem;
          border-bottom: 1px solid #dee2e6;
          border-top-left-radius: calc(.3rem - 1px);
          border-top-right-radius: calc(.3rem - 1px);
        }
    
    .modal a.close-modal {
          position: absolute;
          top: 7px !important;
          right: 7px !important;
          display: block;
          width: 30px;
          height: 30px;
          text-indent: -9999px;
          background-size: contain;
          background-repeat: no-repeat;
          background-position: center center;
          background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAA3hJREFUaAXlm8+K00Acx7MiCIJH/yw+gA9g25O49SL4AO3Bp1jw5NvktC+wF88qevK4BU97EmzxUBCEolK/n5gp3W6TTJPfpNPNF37MNsl85/vN/DaTmU6PknC4K+pniqeKJ3k8UnkvDxXJzzy+q/yaxxeVHxW/FNHjgRSeKt4rFoplzaAuHHDBGR2eS9G54reirsmienDCTRt7xwsp+KAoEmt9nLaGitZxrBbPFNaGfPloGw2t4JVamSt8xYW6Dg1oCYo3Yv+rCGViV160oMkcd8SYKnYV1Nb1aEOjCe6L5ZOiLfF120EjWhuBu3YIZt1NQmujnk5F4MgOpURzLfAwOBSTmzp3fpDxuI/pabxpqOoz2r2HLAb0GMbZKlNV5/Hg9XJypguryA7lPF5KMdTZQzHjqxNPhWhzIuAruOl1eNqKEx1tSh5rfbxdw7mOxCq4qS68ZTjKS1YVvilu559vWvFHhh4rZrdyZ69Vmpgdj8fJbDZLJpNJ0uv1cnr/gjrUhQMuI+ANjyuwftQ0bbL6Erp0mM/ny8Fg4M3LtdRxgMtKl3jwmIHVxYXChFy94/Rmpa/pTbNUhstKV+4Rr8lLQ9KlUvJKLyG8yvQ2s9SBy1Jb7jV5a0yapfF6apaZLjLLcWtd4sNrmJUMHyM+1xibTjH82Zh01TNlhsrOhdKTe00uAzZQmN6+KW+sDa/JD2PSVQ873m29yf+1Q9VDzfEYlHi1G5LKBBWZbtEsHbFwb1oYDwr1ZiF/2bnCSg1OBE/pfr9/bWx26UxJL3ONPISOLKUvQza0LZUxSKyjpdTGa/vDEr25rddbMM0Q3O6Lx3rqFvU+x6UrRKQY7tyrZecmD9FODy8uLizTmilwNj0kraNcAJhOp5aGVwsAGD5VmJBrWWbJSgWT9zrzWepQF47RaGSiKfeGx6Szi3gzmX/HHbihwBser4B9UJYpFBNX4R6vTn3VQnez0SymnrHQMsRYGTr1dSk34ljRqS/EMd2pLQ8YBp3a1PLfcqCpo8gtHkZFHKkTX6fs3MY0blKnth66rKCnU0VRGu37ONrQaA4eZDFtWAu2fXj9zjFkxTBOo8F7t926gTp/83Kyzzcy2kZD6xiqxTYnHLRFm3vHiRSwNSjkz3hoIzo8lCKWUlg/YtGs7tObunDAZfpDLbfEI15zsEIY3U/x/gHHc/G1zltnAgAAAABJRU5ErkJggg==');
        }
        
    .judge_response{
        background-color:#faebd7;
        border-radius: 10px;
        padding: 20px;
    }
    
    @media print {
           .noprint {
              visibility: hidden;
            }
           div {
                break-inside: avoid;
            }
        }
    .g{
        background-color:green;
        color:white;
    }
    .o{
        background-color:orange;
        color:black;
    }
    .table{
        font-size:0.8rem;
    }
    </style>
</head>
<body>
    
    <form  id="myForm">
    
    <div class="tab noprint">
        <button class="tablinks active" onclick="openTab(event, 'llm')"><b>1.</b> Large Language Models</button>
        <button class="tablinks" onclick="openTab(event, 'apikeys')"><b>2.</b> Authorization</button>
        <button class="tablinks" onclick="openTab(event, 'prompt')"><b>3.</b> Prompt</button>
        <button class="tablinks" onclick="openTab(event, 'parameters')"><b>4.</b> LLM Parameters</button>
        <button class="tablinks" onclick="openTab(event, 'judge_tb')"><b>5.</b> Judge</button>
        <button type="submit" style='color:green'><b>Submit To LLMs!</b></button>
        <button class="tablinks btn btn-success my-2 my-sm-0"><a href="../logout.php">Logout&nbsp;&nbsp;<i class="fas fa-sign-out-alt"></i></a></button>
    </div>
    
    <div class="tab noprint">
        <button id='tab2' class="tablinks initiated_hidden" onclick="openTab(event, '[2]')">GPT4o Mini</button>
        <button id='tab5' class="tablinks initiated_hidden" onclick="openTab(event, '[5]')">Claude 3.5 Sonnet</button>
        <button id='tab37' class="tablinks initiated_hidden" onclick="openTab(event, '[37]')">Llama-3.2 90B V</button>
        <button id='tab30' class="tablinks initiated_hidden" onclick="openTab(event, '[30]')">Gemini 1.5 Pro</button>
    </div>

    
    <div class="modal hide fade" id="myModal">
        <div class="modal-header">
            <h3>API - Data Usage</h3>
        </div>
        <div class="modal-body">
            <p style='font-size:0.8rem'>Users of this website are required to agree to the following terms:<br><br>
            The service is a <b>research preview</b>. It only provides limited safety measures and may generate offensive content. 
            It must not be used for any illegal, harmful, violent, racist, or sexual purposes.
            Please do not upload any private information.
            All data are following privacy instructions and terms of use of their API providers.<br><br> 
            <a href="https://help.openai.com/en/articles/7039943-data-usage-for-consumer-services-faq" target="_blank">OpenaAI</a>, 
            <a href="https://support.anthropic.com/en/collections/9811560-data-handling-retention" target="_blank">Anthropic</a>
    </p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn">Close</a>
        </div>
    </div>

    <div class="container-fluid tabcontent" id="llm" style="display:block">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Large Language Model List</h2>
                        <h4>Multi-Selection Panel</h4>
                    </div>
                    <div class="card-body">
                        <div class='row'>
                            <div class='col'>
                                <div>
                                    <h6>OpenAI</h6>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name='model2' class="custom-control-input" id="model2" value="gpt-4o-mini">
                                    <label class="custom-control-label" for="model2">GPT 4o Mini</label>
                                </div>
                            </div>
                            <div class='col'>
                                <div>
                                    <h6>Anthropic</h6>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="model5" value="claude-3-5-sonnet-20240620">
                                    <label class="custom-control-label" for="model5">Claude 3.5 Sonnet</label>
                                </div>
                            </div>
                        </div>
                        <div class='row'><div class="col"><hr></div></div>
                        <div class='row'>
                            <div class='col'>
                                <div>
                                    <h6>Meta</h6>
                                </div>
                                
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="model37" value="meta-llama/Llama-3.2-90B-Vision-Instruct-Turbo">
                                    <label class="custom-control-label" for="model37">Llama 3.2</label>
                                </div>
                            </div>
                            <div class='col'>
                                <div>
                                    <h6>Google</h6>
                                </div>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="model30" value="gemini-1.5-pro">
                                    <label class="custom-control-label" for="model30">Gemini 1.5 Pro </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="apikeys">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Large Language Models</h2>
                        <h4>API Authorization Tokens</h4>
                    </div>
                    
                    <div class="card-body">
                            <div class="two-columns mt-3">
                                <div class="column">
                                    <label for="openaiapikey"><i class="arrow right"></i>&nbsp;&nbsp; OpenAI (<a href='https://platform.openai.com/login?launch' target='_blank'>Create API Token</a>)</label>
                                    <input id='openaiapikey' type='text' class="form-control">
                                    
                                    <label for="aimlapikey"><i class="arrow right"></i>&nbsp;&nbsp; AI/ML (<a href='https://aimlapi.com/app/sign-up/' target='_blank'>Create API Token</a>)</label>
                                    <input id='aimlapikey' type='text' class="form-control">
                                    
                                    <label for="anthropicapikey"><i class="arrow right"></i>&nbsp;&nbsp; Anthropic (<a href='https://console.anthropic.com/' target='_blank'>Create API Token</a>)</label>
                                    <input id='anthropicapikey' type='text' class="form-control">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="prompt">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Large Language Models - Prompt Panel</h2>
                        <h4>Prompt Engineering</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="textarea1"><i class="arrow right"></i>&nbsp;&nbsp;Insert your <b>Prompt</b> here:</label>
                            <textarea class="form-control" id="textarea1" rows="3" placeholder="(Required) Instruct model to generate response to suit your needs"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="prompt_context"><i class="arrow right"></i>&nbsp;&nbsp;Additional <b>Context</b>:</label>
                            <textarea class="form-control" id="prompt_context" rows="3" placeholder="(Optional) Provide additional context information to the model"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="judge_tb">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>LLM Judge Panel</h2>
                        <h4>Applies to all models</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="judge_prompt"><i class="arrow right"></i>&nbsp;&nbsp;Insert your <b>Judges Prompt</b> here:</label>
                            <textarea class="form-control" id="judge_prompt" rows="3" placeholder="(Optional) Judge LLM Model instructions"></textarea>
                            <hr>
                            <p>
                                <i>
                                    Judge LLM Parameters: 
                                    <b>Temperature</b> = 0, 
                                    <b>Max Tokens</b> = Unlimited
                                </i>
                            </p>
                        </div>
                        <div>
                            <select class="form-select" id='judge'>
                                <option value="gpt-4o-mini" selected>OpenAI GPT-4o Mini</option>
                                <option value="claude-3-5-sonnet-20240620">Anthropic Sonnet 3.5</option>
                                <option value="meta-llama/Llama-3.2-90B-Vision-Instruct-Turbo">Meta Llama 3.2</option>
                                <option value="gemini-1.5-pro">Google Gemini 1.5 Pro</option>
                            </select>
                        </div>    
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="parameters">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Large Language Models - Parameters</h2>
                        <h4>Define the parameters used by models</h4>
                        <h6><span style='color:gray'>Currently only parameters with <font style='color:red; font-weight:bolder'>*</font> are applied.</span></h6>
                    </div>
                    
                    <div class="card-body">
                        <div class="two-columns mt-3">
                            <div class="column">
                                
                                <label for="tokens"><i class="arrow right"></i>&nbsp;&nbsp; tokens <font style='color:red; font-weight:bolder'>*</font>&nbsp;<span class='labelinfo'>: This parameter specifies the maximum number of tokens (words or pieces of words) that the model will generate in response to the prompt. </span></label>
                                <input id='tokens' type='number' min="0" max="128000" step="1" value="1024" class="form-control">
                                
                                <label for="top_p"><i class="arrow right"></i>&nbsp;&nbsp; top_p <font style='color:red; font-weight:bolder'>*</font>&nbsp;<span class='labelinfo'>: This setting limits the model's choices to a percentage of likely tokens.</span></label>
                                <input id='top_p' type='number' min="0" max="1" step="0.1" value="1" class="form-control">
                                
                                <label for="temperature"><i class="arrow right"></i>&nbsp;&nbsp; temperature <font style='color:red; font-weight:bolder'>*</font>&nbsp;<span class='labelinfo'>: This setting influences the variety in the model's responses.</span></label>
                                <input id='temperature' type='number' min="0" max="2" step="0.1" value="1" class="form-control"> 
                                
                            </div>
                            <div class="column">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <div class="container-fluid tabcontent" id="[2]">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>LLM : <b>GPT-4o-Mini</b></h2>
                        <h4>Generated Response Panel</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json">
                                <label for="responseTextAreaJson"><b>Απάντηση Μοντέλου σε JSON</b></label>
                                <textarea class="form-control  mt-3" id="responseTextAreaJson_gpt-4o-mini" rows="20" readonly></textarea>
                            </div>
                            <div class="column">
                                <u><h3>LLM Response</h3></u>
                                <!--<label for="emptyDiv"><b>Parsed Markdown LLM Response</b></label>-->
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsed_gpt-4o-mini"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Modified 10/10/2024 -- Judge Service-->
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json"></div>
                            <div class="column judge_response">
                                <u><h3>LLM Response Evaluation from:</h3></u>
                                <!--<label for="emptyDiv"><b>LLM Response Evaluation from:</b></label>-->
                                <div id='gpt-4o-mini' class="text-center"><img src="images/gears.gif" style="width:15%"></div>
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsedJudge_gpt-4o-mini"></div>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="[5]">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>LLM : <b>Claude 3.5 Sonnet</b></h2>
                        <h4>Generated Response Panel</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json">
                                <label for="responseTextAreaJson"><b>Απάντηση Μοντέλου σε JSON</b></label>
                                <textarea class="form-control  mt-3" id="responseTextAreaJson_claude-3-5-sonnet-20240620" rows="20" readonly></textarea>
                            </div>
                            <div class="column">
                                <u><h3>LLM Response</h3></u>
                                <!--<label for="emptyDiv"><b>Parsed Markdown LLM Response</b></label>-->
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsed_claude-3-5-sonnet-20240620"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Modified 10/10/2024 -- Judge Service-->
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json"></div>
                            <div class="column judge_response">
                                <u><h3>LLM Response Evaluation from:</h3></u>
                                <!--<label for="emptyDiv"><b>LLM Response Evaluation from:</b></label>-->
                                <div id='claude-3-5-sonnet-20240620' class="text-center"><img src="images/gears.gif" style="width:15%"></div>
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsedJudge_claude-3-5-sonnet-20240620"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="[37]">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>LLM : <b>Llama 3.2 90B V</b></h2>
                        <h4>Generated Response Panel</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json">
                                <label for="responseTextAreaJson"><b>Απάντηση Μοντέλου σε JSON</b></label>
                                <textarea class="form-control  mt-3" id="responseTextAreaJson_meta-llama-Llama-3.2-90B-Vision-Instruct-Turbo" rows="20" readonly></textarea>
                            </div>
                            <div class="column">
                                <u><h3>LLM Response</h3></u>
                                <!--<label for="emptyDiv"><b>Parsed Markdown LLM Response</b></label>-->
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsed_meta-llama-Llama-3.2-90B-Vision-Instruct-Turbo"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Modified 10/10/2024 -- Judge Service-->
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json"></div>
                            <div class="column judge_response">
                                <u><h3>LLM Response Evaluation from:</h3></u>
                                <!--<label for="emptyDiv"><b>LLM Response Evaluation from:</b></label>-->
                                <div id='meta-llama-Llama-3.2-90B-Vision-Instruct-Turbo' class="text-center"><img src="images/gears.gif" style="width:15%"></div>
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsedJudge_meta-llama-Llama-3.2-90B-Vision-Instruct-Turbo"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid tabcontent" id="[30]">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>LLM : <b>Gemini 1.5 Pro</b></h2>
                        <h4>Generated Response Panel</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json">
                                <label for="responseTextAreaJson"><b>Απάντηση Μοντέλου σε JSON</b></label>
                                <textarea class="form-control  mt-3" id="responseTextAreaJson_gemini-1.5-pro" rows="20" readonly></textarea>
                            </div>
                            <div class="column">
                                <u><h3>LLM Response</h3></u>
                                <!--<label for="emptyDiv"><b>Parsed Markdown LLM Response</b></label>-->
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsed_gemini-1.5-pro"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Modified 10/10/2024 -- Judge Service-->
                        
                        <div class="two-columns mt-3">
                            <div class="column model_response_json"></div>
                            <div class="column judge_response">
                                <u><h3>LLM Response Evaluation from:</h3></u>
                                <!--<label for="emptyDiv"><b>LLM Response Evaluation from:</b></label>-->
                                <div id='gemini-1.5-pro' class="text-center"><img src="images/gears.gif" style="width:15%"></div>
                                <div id="emptyDiv">
                                    <div class="mt-3" id="responseTextAreaParsedJudge_gemini-1.5-pro"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    </form>
    
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/markdown-it@14.1.0/dist/markdown-it.min.js"></script>
    
<script>
$(document).ready(function() {
        
    $("#myForm").submit(function(event) {
        
        event.preventDefault();
        
        document.getElementById("tab2").style.display = "none";
        document.getElementById("tab5").style.display = "none";
        document.getElementById("tab30").style.display = "none";
        document.getElementById("tab37").style.display = "none";

        var selected_models = [];
        $('.custom-control-input:checked').each(function(){
            selected_models .push($(this).val());
            })
        
        selected_models.forEach(
            element => {
                run_llm_iteration(element, "#textarea1", "#llmApiKey", "#openaiapikey", "#anthropicapikey", "#aimlapikey")
                console.log("called:" + element);
                });
    });
    
});
    
function openTab(evt, tabName) {
        evt.preventDefault(); // Prevent form submission
      var i, tabcontent, tablinks;
      
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }
      
      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      
      document.getElementById(tabName).style.display = "block";
      evt.currentTarget.className += " active";
    }

$(window).on('load', function() {
    $('#myModal').modal('show');
});

const md = window.markdownit();
    
        
function run_llm_iteration(llm, prompt_, apikey, openaikey, anthropicapikey, aimlapikey) {
            
    var formData = {
        prompt: $(prompt_).val(),
        llmApiKey: $(apikey).val(),
        openaiApiKey: $(openaikey).val(),
        anthropicApikey: $(anthropicapikey).val(),
        aimlApikey: $(aimlapikey).val(),
        modelSelection: llm,
        temperature: $(temperature).val(),
        tokens: $(tokens).val(),
        top_p: $(top_p).val(),
        prompt_context: $(prompt_context).val()
    };
            
    $.ajax({
        type: "POST",
        url: "https://lps.dev-maister.gr/demo/ai/llm_api_v7_distilled.php/simple",
        data: JSON.stringify(formData),
        start_time: new Date().getTime(),
        contentType: "application/json",
        success: function(response) {
            var replaced_character_of_model = "#responseTextAreaJson_" + llm.replace("/", "-");

            $(replaced_character_of_model).val(JSON.stringify(response, null, 2));
            
            if(llm=="gpt-4o-mini"){

                var specificValue = response.choices[0].message.content;
                var prompt_tokens = response.usage['prompt_tokens'];
                var completion_tokens = response.usage['completion_tokens'];
                var total_tokens = response.usage['total_tokens'];
                
                const parse_markdown = md.render(specificValue);
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML = null;
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += parse_markdown;
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<br><br>";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Prompt Tokens: </b><span style='color:green'>" + prompt_tokens + "&nbsp;&nbsp;";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Completion Tokens: </b><span style='color:green'>" + completion_tokens + "&nbsp;&nbsp;";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Total Tokens: </b><span style='color:green'>" + total_tokens + "&nbsp;&nbsp;";
                
                document.getElementById("tab2").style.display = "block";
                run_llm_judge(llm, "#textarea1", "#llmApiKey", "#openaiapikey", "#anthropicapikey", "#aimlapikey", specificValue);
                
            
            } else if (llm=="claude-3-5-sonnet-20240620"){
                
                var specificValue1 = response.content[0].text;
                var prompt_tokens = response.usage['input_tokens'];
                var completion_tokens = response.usage['output_tokens'];
                var total_tokens = parseInt(prompt_tokens) + parseInt(completion_tokens);
                
                const parse_markdown_claude = md.render(specificValue1);
                
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML = null;
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += parse_markdown_claude;
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<br><br>";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Prompt Tokens: </b><span style='color:green'>" + prompt_tokens + "&nbsp;&nbsp;";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Completion Tokens: </b><span style='color:green'>" + completion_tokens + "&nbsp;&nbsp;";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Total Tokens: </b><span style='color:green'>" + total_tokens + "&nbsp;&nbsp;";
                
                document.getElementById("tab5").style.display = "block";
                run_llm_judge(llm, "#textarea1", "#llmApiKey", "#openaiapikey", "#anthropicapikey", "#aimlapikey", specificValue1);
                
            } else {
                
                llm = llm.replace("/", "-");

                var specificValue2 = response.choices[0].message.content;
                var prompt_tokens = response.usage['prompt_tokens'];
                var completion_tokens = response.usage['completion_tokens'];
                var total_tokens = response.usage['total_tokens'];
            
                const parse_markdown_aiml = md.render(specificValue2);
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML = null;
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += parse_markdown_aiml;
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<br><br>";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Prompt Tokens: </b><span style='color:green'>" + prompt_tokens + "&nbsp;&nbsp;";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Completion Tokens: </b><span style='color:green'>" + completion_tokens + "&nbsp;&nbsp;";
                document.getElementById("responseTextAreaParsed_"+llm).innerHTML += "<b>Total Tokens: </b><span style='color:green'>" + total_tokens + "&nbsp;&nbsp;";
                
                
                switch(llm){
                    case "gemini-1.5-pro":
                        document.getElementById("tab30").style.display = "block";
                        run_llm_judge(llm, "#textarea1", "#llmApiKey", "#openaiapikey", "#anthropicapikey", "#aimlapikey", specificValue2);
                        break;
                    case "meta-llama-Llama-3.2-90B-Vision-Instruct-Turbo":
                        document.getElementById("tab37").style.display = "block";
                        run_llm_judge(llm, "#textarea1", "#llmApiKey", "#openaiapikey", "#anthropicapikey", "#aimlapikey", specificValue2);
                        break;
                    default:
                        console.warn("Unsupported LLM selected:", llm);
                        break;
                }
        }},
        error: function(error) {
            console.error("Error submitting form data:", error);
        }
    });
}

function run_llm_judge(llm, prompt_, apikey, openaikey, anthropicapikey, aimlapikey, llm_response) {
            
    var judgeSelect = document.getElementById("judge");
    var judge = judgeSelect.value;
            
    var judgeData = {
        prompt: $(prompt_).val(),
        llmApiKey: $(apikey).val(),
        openaiApiKey: $(openaikey).val(),
        anthropicApikey: $(anthropicapikey).val(),
        aimlApikey: $(aimlapikey).val(),
        modelSelection: llm,
        temperature: $(temperature).val(),
        tokens: $(tokens).val(),
        top_p: $(top_p).val(),
        prompt_context: $(prompt_context).val(),
        model_response: llm_response,
        judge: judge,
        judge_prompt: $(judge_prompt).val()
    };
            
    $.ajax({
        type: "POST",
        url: "https://lps.dev-maister.gr/demo/ai/llm_judge_api_v1_distilled.php",
        data: JSON.stringify(judgeData),
        contentType: "application/json",
        success: function(response) {
            
            document.getElementById(llm).style.display = "none";
            
            // OpenAI AIML
            try {
              var judgeValue = response.choices[0].message.content;
              var model_name = response.model;
              var prompt_tokens = response.usage['prompt_tokens'];
              var completion_tokens = response.usage['completion_tokens'];
              var total_tokens = response.usage['total_tokens'];
            }
            catch(err) {
              console.error("Not an OpenAI/AIML LLM, so no response!");
            }
            
            // Anthropic
            try {
              var judgeValue = response.content[0].text;
              var model_name = response.model;
              var prompt_tokens = response.usage['input_tokens'];
              var completion_tokens = response.usage['output_tokens'];
              var total_tokens = parseInt(prompt_tokens) + parseInt(completion_tokens);
            }
            catch(err) {
              console.error("Not an Anthropic LLM, so no response!");
            }
            
            var pm = md.render(judgeValue);
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML = null;
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML += "<b>LLM Judge</b>: <span style='color:blue'>" + model_name + "</span><br><br>";
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML += pm;
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML += "<br><br>";
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML += "<b>Prompt Tokens: </b><span style='color:green'>" + prompt_tokens + "&nbsp;&nbsp;";
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML += "<b>Completion Tokens: </b><span style='color:green'>" + completion_tokens + "&nbsp;&nbsp;";
            document.getElementById("responseTextAreaParsedJudge_"+llm).innerHTML += "<b>Total Tokens: </b><span style='color:green'>" + total_tokens + "&nbsp;&nbsp;";
        },
        error: function(error) {
            console.error("Error nested ajax form data:", error);
        }
    });
}

</script>
</body>
</html>