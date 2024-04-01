<style>
    #left-sidebar { left: -450px;}
    #main-content { width: 100%; }
    .hide
    {
        display:none;
    }
    .input-group-text{
        font-size:0.8em;
    }
    .subect-sec label{
       
    }
    
    table.dataTable {
        clear: none !important;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        max-width: none !important;
        border-collapse: collapse !important;
    }
    button, input {
        overflow: visible;
        border-color: transparent;
    }
    .report-but-yel{
        background: -webkit-linear-gradient(top, #1f1f4e, #1f1f4e);
        width: 130px !important;
        height: 50px !important;
        font-size: 16px !important;
        color: #fff;
    }
    
    .report-but-yel:hover{
        background-color: #FBC358;
        background: -webkit-linear-gradient(top, #FBC358, #FBC358);
        color: #000;
    }
    .report-but-blue{
        background: linear-gradient( 180deg, #1f1f4e 0%, #1f1f4e 50%, #1f1f4e 100%);
        width: 130px !important;
        color: #fff;
        height: 50px !important;
        font-size: 16px !important;
    }
    
    .report-but-yel:hover{
       background-color: #FBC358;
       background: linear-gradient( 180deg, #FBC358 0%, #FBC358 50%, #FBC358 100%);
        color: #000;
    }
    /* Chrome, Safari, Edge, Opera */
    
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
    .container {
        max-width: 100% !important;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 0px !important;
    }
    label {
        display: initial !important;
        margin-bottom: 0px !important;
    }
    
    .big-section label {
        font-weight: 600 !important;
        padding: 0px !important;
    }
    .row.header-sec {
        justify-content: center;
    }
    
    td.id_name {
        border: 0px !important;
        border-top: 2px solid #fff !important;
        border-bottom: 2px solid #fff !important;
    }
    .dark_border {
        position: relative;
        background: #000;
        height: 3px;
        margin: 15px 0;
    }
    .body.body-bg {
        padding: 15px !important;
    }
    .three_section {
        border-bottom: 3px solid;
        padding: 11.5px 0;
    }
    .ritz .waffle a {
        color: inherit;
    }
    .ritz .waffle .s22 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s0 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 24pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s8 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 8pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s31 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s38 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s4 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 16pt;
        vertical-align: top;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s3 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 14pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s18 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s50 {
        border-bottom: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s15 {
        border-bottom: 1px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s46 {
        border-bottom: 3px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: top;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s35 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: center;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s27 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s49 {
        border-bottom: 3px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s12 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s25 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s44 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s48 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s53 {
        border-bottom: 3px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s23 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s21 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s42 {
        border-bottom: 1px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s7 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s9 {
        border-bottom: 1px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 8pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s11 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s54 {
        border-bottom: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s41 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s14 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s55 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s28 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s29 {
        border-bottom: 1px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s26 {
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s10 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s40 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s33 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: top;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s32 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s2 {
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 14pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s34 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: center;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s36 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s17 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s47 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: top;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s52 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s5 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 13pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s1 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 18pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s43 {
        border-bottom: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s6 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s45 {
        border-bottom: 1px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s37 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: top;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s24 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s51 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s56 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s39 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #000000;
        text-align: left;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s30 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s13 {
        border-bottom: 1px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'docs-Calibri',Arial;
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s20 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 12pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s19 {
        border-bottom: 3px SOLID #000000;
        border-right: 3px SOLID #000000;
        background-color: #ffffff;
        text-align: center;
        color: #000000;
        font-family: 'Arial';
        font-size: 11pt;
        vertical-align: middle;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }

    .ritz .waffle .s16 {
        border-bottom: 3px SOLID #000000;
        border-right: 1px SOLID #000000;
        background-color: #ffffff;
        text-align: left;
        font-weight: bold;
        color: #000000;
        font-family: 'Times New Roman';
        font-size: 11pt;
        vertical-align: bottom;
        white-space: nowrap;
        direction: ltr;
        padding: 2px 3px 2px 3px;
    }
.row-headers-background{
    display: none;
}
.back_button{
        margin: 17px;
    display: inline-block;
    position: relative;
    left: calc( 100% - 100px);
}
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=STIX+Two+Math&display=swap" rel="stylesheet"> 
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card" style="padding-bottom: 1rem;">
             <a href="javascript:void(0)" title="Back" class="btn btn-sm btn-success back_button" onclick="goBack()" >Back</a>

<?php 
if($report_marks['status'] == '2'){
    $myreadonl = "readonly";
} else{
    $myreadonl = ""; 
}
?>

    <!----Form starts ---------------->
    
    <?php echo $this->Form->create(false , ['url' => ['action' => 'fifth_record'] , 'id' => "subreportform" , 'method' => "post"  ]); ?>
    <input type="hidden" name="studentid" value="<?=$_GET['studentid'];?>">
    <input type="hidden" name="classid" value="<?=$_GET['classid'];?>">



            <div class="body body-bg" style="text-transform: uppercase;color: #444;padding: 20px;font-weight: 400;border: 2px solid #333;margin: 20px;    width: 1260px;    margin: 0 auto;">
              
                <div class="row clearfix" style="display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;">
                    <div class="col-sm-12" style="padding: 0px; flex: 0 0 100%;max-width: 100%;position: relative;width: 100%;">
                        <div class="table-responsive" style="display: block;width: 100%;overflow-x: auto;"><br><br>
                         
                         <div class="container" style="max-width: 100% !important;">
<div class="row header-sec" style="padding: 12px 0;display: -ms-flexbox;display: flex;margin-right: -15px;margin-left: -15px;">
<div class="col-md-2" style="-ms-flex: 0 0 14.666667%;flex: 0 0 14.666667%;max-width: 16.666667%;position: relative;width: 100%;padding-right: 15px;padding-left: 15px;">
 <div class="logo">
 <img src="<?=$baseurl ?>img/logoSecond.png" style="width: 100%;">  
 </div> 
</div>
<div class="col-md-10" style="-ms-flex: 0 0 64.666667%;flex: 0 0 64.666667%;max-width: 66.666667%;position: relative;width: 100%;padding-right: 15px;padding-left: 15px;">
  <div class="main-head" style="text-align: center;">
   <h3 style="font-size:  29px !important; color: #000;line-height: 42px;text-transform: uppercase;font-weight: 600;font-family: arial !important;">
      RÉPUBLIQUE DEMOCRATIQUE DU CONGO
MINISTERE DE L’ENSEIGNEMENT PRIMAIRE, SECONDAIRE 
ET PROFESSIONNEL
</h3>
</div>
</div>
  
</div>   
</div>
<div class="dark_border"></div> 
<div class="container">
    <div class="up-header">
        <div class="pol-md-12">
  <table id="example" cellpadding="0" cellspacing="0" class="display nowrap no-footer head-bot dataTable" style="width: 100%;    clear: none !important;margin-top: 0px !important;margin-bottom: 0px !important;max-width: none !important;border-collapse: collapse !important;" role="grid" aria-describedby="example_info">
                        <thead> </thead>
                        <tbody>
                        <tr class="" style="font-size: 12px;font-weight: 500;background-color: #fff;border-top: 2px solid #333;border-bottom: 2px solid #333;">
                        <td class="id_name" style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;    font-family: arial !important;">N° ID.</td>
                                                   
                        <?php $nid = $report_marks->nid;
            if($nid){ ?>
        
          <?php $report_marks; $nid = $report_marks->nid; $nid_array = explode(",",$nid); ?>
            <?php foreach($nid_array as $key => $value)
{ ?>
        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="<?php echo $value; ?>" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
        
        <?php } } else{ ?>
        
         <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
                             
                        <td style="width: 141px;border-top: none;font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 8px !important;border-left: 2px solid #333;background: #fff;box-sizing: content-box;"><input type="text" name="nid[]" value="" maxlength="1"   required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;overflow: visible;"></td>
        
            <?php } ?>
                      </tr>

                    </tbody>

                                    </table>
</div>
    </div>
    
</div>

<div class="dark_border" style="margin-bottom: 0;"></div>

<div class="container">
    <div class="up-header">

    </div>
    
</div>

<div class="container">
<div class="up-header">





<div class="row" style="display: flex;">

<div class="col-md-6" style="width: 50%;    padding: 0 15px;">
<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 200px;font-family: arial !important;">
  PROVINCE :
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="province" value="<?php $code = $report_marks->province;if($code){   echo $code; } ?>"   style="font-weight: bold;font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;height: 38px;border: none;margin: 0;">
</div>
</div>
<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 200px;font-family: arial !important;">
VILLE :
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="" value="<?php echo $city; ?>"   style="font-weight: bold;font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;height: 38px;border: none;margin: 0;">
</div>
</div>
<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 200px;font-family: arial !important;">
  COMMUNE/TER. (1) :
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="eleve" value="<?php $code = $report_marks->eleve;if($code){   echo $code; } ?>"   style="font-weight: bold;font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;height: 38px;border: none;margin: 0;">
</div>
</div>
<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 200px;font-family: arial !important;">
  ECOLE :
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="falt" value="<?php $code = $report_marks->falt;if($code){   echo $code; } ?>"   style="font-weight: bold;font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;height: 38px;border: none;margin: 0;">
</div>
</div>

<div class="inline" style="display: flex;    margin-top: 20px;"> 
<div class="lable" style="margin-top: 22px;font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 110px;font-family: arial !important;">
CODE

</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<table id="example" class="display nowrap dataTable no-footer head-bot" role="grid" aria-describedby="example_info" style="width: 100%;margin-top: 0 !important;clear: none !important;margin-bottom: 0px !important;max-width: none !important;border-collapse: collapse !important;">
                        <thead> </thead>
                        <tbody>
                        <tr class="odd" style="border-top: 2px solid #333;border-bottom: 2px solid #333;font-size: 12px;font-weight: 500;background-color: #f9f9f9;">
                        <?php $code = $report_marks->code;
            if($code){ ?>
        
          <?php $report_marks; $nid = $report_marks->code; $nid_array = explode(",",$nid); ?>
            <?php foreach($nid_array as $key => $value)
{ ?>
       <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="<?php echo $value; ?>" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                       
        <?php } } else{ ?>
        
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="code[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                         <?php } ?>                         
                      </tr>

                    </tbody>

                                    </table>
</div>
</div>
</div>




<div class="col-md-6 border-main-left" style="width: 50%;    padding: 0 15px;border-left: 2px solid #333;
    padding-bottom: 12px;">
    <div class="row">
        <div class="col-md-12">
<div class="row" style="display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;">
<div class="col-md-9" style="">

<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 125px;font-family: arial !important;">
ELEVE:
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="studentname" style="font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;border: none;height: 38px;" value="<?php echo $student_name['f_name'] . " " . $student_name['l_name']; ?>"  >
</div>
</div>
</div>

<div class="col-md-3" style="">

<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 80px;font-family: arial !important;">
Sexe
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="" value="<?php echo $gender; ?>"   style="font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;border: none;height: 38px;">
</div>
</div>
</div>
</div></div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-9"><div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 80px;font-family: arial !important;">
Né(e) à :
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="neea" style="font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;border: none;height: 38px;" value="<?php $code = $report_marks->neea;if($code){   echo $code; } ?>"  >
</div>
</div></div>
                <div class="col-md-3" style="    display: flex;">
                    <div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 38px;font-family: arial !important;">
,le
</div>
<div class="ville-bot" style="width: 100%;margin-left: 3px;">
<input type="text" name="lethe" style="font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;border: none;height: 38px;" value="<?php $code = $report_marks->lethe;if($code){   echo $code; } ?>"  >
</div>
                </div>
            </div>
            

        </div>
    </div>
<div class="col-md-12" style="width: 100%; padding: 0px;">

<div class="inline" style="display: flex;">  
<div class="lable" style="font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 112px;font-family: arial !important;">
CLASSE :
</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<input type="text" name="" value="<?php echo $classes_name->c_name; ?>"  style="font-weight: bold;border-bottom: 1px dashed #333 !important;width: 100%;border-color: transparent;border: none;height: 38px;">
</div>
</div>
</div>
<div class="row"><div class="col-md-12" style="    margin-top: 50px;">
<div class="inline" style="display: flex;    margin-top: 6px;"> 
<div class="lable" style="margin-top: 22px;font-size: 14px;font-weight: 600;margin-top: 10px;float: left;width: 110px;font-family: arial !important;">
MATRICULE

</div>
<div class="ville-bot" style="width: 100%;margin-left: 5px;">
<table id="example" class="display nowrap dataTable no-footer head-bot" role="grid" aria-describedby="example_info" style="width: 100%;margin-top: 0 !important;clear: none !important;margin-bottom: 0px !important;max-width: none !important;border-collapse: collapse !important;">
                        <thead> </thead>
                        <tbody>
                        <tr class="odd" style="border-top: 2px solid #333;border-bottom: 2px solid #333;font-size: 12px;font-weight: 500;background-color: #f9f9f9;">
                                                 
                         <?php $code = $report_marks->nperm;
            if($code){ ?>
        
          <?php $report_marks; $nid = $report_marks->nperm; $nid_array = explode(",",$nid); ?>
            <?php foreach($nid_array as $key => $value)
{ ?>
        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="<?php echo $value; ?>" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
        <?php } } else{ ?>
                                                 
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                             
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
                        <td style="font-size: 13px;white-space: nowrap;border-right: 2px solid #333;padding: 8px 6px !important;border-left: 2px solid #333;background: #fff;border-top: none !important;"><input type="text" name="nperm[]"   maxlength="1" value="" required="required" style="font-weight: bold;border: none;width: 22px;height: 28px;"></td>
<?php } ?>                                 
                      </tr>

                    </tbody>

                                    </table>
</div>
</div>
</div></div>
</div>
</div>


</div>

</div>
</div>
<div class="dark_border" style="margin-top: 0px;"></div>
<div class="container">
<div class="row">
        <div class="col-md-7"><b style="text-align: center;display: block;font-size: 16px;">BULLETIN DE L'ELEVE  DEGRE TERMINAL (5ème ANNEE) </b></div>
        <div class="col-md-5"><b style="text-align: center;display: block;font-size: 16px;">ANNEE SCOLAIRE 20<input type="text" name="" value="" style="font-weight: bold;border-bottom: 1px dashed #333 !important;  width: 40px;border-color: transparent;border: none;height: 38px;">20<input type="text" name="" value="" style="font-weight: bold;border-bottom: 1px dashed #333 !important;  width: 40px;border-color: transparent;border: none;height: 38px;"></b></div>
</div> 
</div>
<div class="dark_border" style="margin-bottom: 0px;"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12" style="    padding: 0;">
<div class="ritz grid-container" dir="ltr">
  <table class="waffle" style="width: 100%" cellspacing="0" cellpadding="0">
  
    <tbody>
           
            
            <tr style="height: 20px">
                <th id="645166884R5" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">6</div>
                </th>
                <td class="s5" rowspan="2">BRANCHES</td>
                <td class="s6" dir="ltr" colspan="7">PREMIER TRIMESTRE</td>
                <td class="s6" dir="ltr" colspan="7">DEUXIEME TRIMESTRE</td>
                <td class="s6" dir="ltr" colspan="7">TROISIEME TRIMESTRE</td>
                <td class="s7" dir="ltr" colspan="2">TOTAL</td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R6" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">7</div>
                </th>
                <td class="s8" dir="ltr">
                    MAX<br>per
                </td>
                <td class="s8" dir="ltr">1eP</td>
                <td class="s8" dir="ltr">2eP</td>
                <td class="s8" dir="ltr">
                    MAX<br>EX
                </td>
                <td class="s8" dir="ltr">
                    PTS<br>OBT.
                </td>
                <td class="s8" dir="ltr">
                    MAX<br>TRIM.
                </td>
                <td class="s8" dir="ltr">
                    PTS<br>OBT.
                </td>
                <td class="s8" dir="ltr">
                    MAX<br>per
                </td>
                <td class="s8" dir="ltr">3eP</td>
                <td class="s8" dir="ltr">4eP</td>
                <td class="s8" dir="ltr">
                    MAX<br>EX
                </td>
                <td class="s8" dir="ltr">
                    PTS<br>OBT.
                </td>
                <td class="s8" dir="ltr">
                    MAX<br>TRIM.
                </td>
                <td class="s8" dir="ltr">
                    PTS<br>OBT.
                </td>
                <td class="s8" dir="ltr">
                    MAX<br>per
                </td>
                <td class="s8" dir="ltr">5eP</td>
                <td class="s8" dir="ltr">6eP</td>
                <td class="s8" dir="ltr">
                    MAX<br>EX
                </td>
                <td class="s8" dir="ltr">
                    PTS<br>OBT.
                </td>
                <td class="s8" dir="ltr">
                    MAX<br>TRIM.
                </td>
                <td class="s8" dir="ltr">
                    PTS<br>OBT.
                </td>
                <td class="s8" dir="ltr">
                    MAX<br>TRIM.
                </td>
                <td class="s9" dir="ltr">
                    PTS<br>OBT.
                </td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R7" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">8</div>
                </th>
                <td class="s7" dir="ltr" colspan="24">DOMAINE DES LANGUES</td>
            </tr>
            <tr style="height: 20px;border-top: 2px solid #000;">
                <th id="645166884R8" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">9</div>
                </th>
                <td class="s10" dir="ltr" colspan="24">LANGUES CONGOLAISES</td>
            </tr>
<tr style="height: 20px">
<th id="645166884R9" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">10</div>
</th>
<td class="s11" dir="ltr">Expression Orale</td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="first_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="first_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="first_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="first_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="first_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="first_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="first_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="first_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="first_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="first_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $first_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="first_section11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="first_section11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="first_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">240</td>
<td class="s15"><input type="text" name="" class="first_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R10" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">11</div>
</th>
<td class="s11" dir="ltr">Expression Ecrite</td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="second_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="second_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>    
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="second_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="second_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="second_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="second_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="second_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="second_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="second_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section9second_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s14">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="second_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $second_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="second_section11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="second_section11second_section11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="second_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">240</td>
<td class="s15"><input type="text" name="" class="second_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R11" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">12</div>
    </th>
    <td class="s16" dir="ltr">Sous-Total</td>
    <td class="s17" dir="ltr">40</td>
    <td class="s17"><input type="text" name="" class="forty_section1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="forty_section2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="forty_section3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">160</td>
    <td class="s18"><input type="text" name="" class="forty_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s17"><input type="text" name="" class="forty_section5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="forty_section6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="forty_section7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">160</td>
    <td class="s18"><input type="text" name="" class="forty_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s17"><input type="text" name="" class="forty_section9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="forty_section10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="forty_section11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">160</td>
    <td class="s18"><input type="text" name="" class="forty_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">480</td>
    <td class="s19"><input type="text" name="" class="forty_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R12" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">13</div>
    </th>
    <td class="s20" dir="ltr" colspan="17">FRANCAIS</td>
</tr>
<tr style="height: 20px">
<th id="645166884R13" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">14</div>
</th>
<td class="s11" dir="ltr">Vocabulaire</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_one1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one1tens_section_one1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>    

</td>
<td class="s14">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_one3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one4" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one4"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> </td>
<td class="s14">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_one8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s14" style="border-top: 3px solid #000;"> <?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_one10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr"  style="border-top: 3px solid #000;">20</td>
<td class="s14"  style="border-top: 3px solid #000;"><?php  if($no = $third_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_one11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_one11tens_section_one11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr"  style="border-top: 3px solid #000;">40</td>
<td class="s14"  style="border-top: 3px solid #000;"><input type="text" name="" class="tens_section_one12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr"  style="border-top: 3px solid #000;">120</td>
<td class="s15"  style="border-top: 3px solid #000;"><input type="text" name="" class="tens_section_one13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R14" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">15</div>
</th>
<td class="s11" dir="ltr">Expression Orale</td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="third_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s14">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="third_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section2third_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="third_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section3third_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="third_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="third_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> </td>
<td class="s14">

<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="third_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td> 
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="third_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="third_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">  <?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="third_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s14">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="third_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $fourth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="third_section11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="third_section11third_section11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="third_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">240</td>
<td class="s15"><input type="text" name="" class="third_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R15" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">16</div>
    </th>
    <td class="s16" dir="ltr">Sous-Total</td>
    <td class="s17" dir="ltr">30</td>
    <td class="s25"><input type="text" name="" class="thirty_section_one1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="thirty_section_one2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">60</td>
    <td class="s18"><input type="text" name="" class="thirty_section_one3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">120</td>
    <td class="s18"><input type="text" name="" class="thirty_section_one4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">30</td>
    <td class="s25"><input type="text" name="" class="thirty_section_one5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="thirty_section_one6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">60</td>
    <td class="s18"><input type="text" name="" class="thirty_section_one7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">120</td>
    <td class="s18"><input type="text" name="" class="thirty_section_one8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">30</td>
    <td class="s25"><input type="text" name="" class="thirty_section_one9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="thirty_section_one10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">60</td>
    <td class="s18"><input type="text" name="" class="thirty_section_one11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">120</td>
    <td class="s18"><input type="text" name="" class="thirty_section_one12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">360</td>
    <td class="s19"><input type="text" name="" class="thirty_section_one13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px;">
    <th id="645166884R16" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">17</div>
    </th>
    <td class="s26" dir="ltr" colspan="24" style="border-bottom: 2px solid #000;">LECT. - ECRITURE EN</td>
</tr>
<tr style="height: 20px;">
<th id="645166884R17" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">18</div>
</th>
<td class="s27" dir="ltr">LANGUES CONGOLAISES</td>
<td class="s17" dir="ltr">30</td>
<td class="s25">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="thirty_section_second1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s18">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="thirty_section_second2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s17" dir="ltr">60</td>
<td class="s18">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="thirty_section_second3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s17" dir="ltr">120</td>
<td class="s18"><input type="text" name="" class="thirty_section_second4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">30</td>
<td class="s25">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="thirty_section_second5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> </td>
<td class="s18">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="thirty_section_second6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">60</td>
<td class="s18">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="thirty_section_second7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">120</td>
<td class="s18"><input type="text" name="" class="thirty_section_second8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">30</td>
<td class="s25">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="thirty_section_second9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s18">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="thirty_section_second10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s17" dir="ltr">60</td>
<td class="s18">
<?php  if($no = $fifth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="thirty_section_second11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="thirty_section_second11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">120</td>
<td class="s18"><input type="text" name="" class="thirty_section_second12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">360</td>
<td class="s19"><input type="text" name="" class="thirty_section_second13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R18" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">19</div>
    </th>
    <td class="s7" dir="ltr" colspan="24">DOMAINE DES MATHEMATIQUES, SCIENCES ET TECHNOLOGIE</td>
</tr>
<tr style="height: 20px">
    <th id="645166884R19" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">20</div>
    </th>
    <td class="s10" dir="ltr" colspan="24">MATHEMATIQUES</td>
</tr>
<tr style="height: 20px">
<th id="645166884R20" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">21</div>
</th>
<td class="s28" dir="ltr">Mesures des grandeurs</td>
<td class="s29" dir="ltr">10</td>
<td class="s11" dir="ltr">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_second1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s14">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_second2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_second3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second3tens_section_second3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_second4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_second5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second5tens_section_second5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> </td><input type="text" name="" class="tens_section_second5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s14">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_second6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_second7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second7tens_section_second7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_second8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_second9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_second10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second10tens_section_second10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $sixth_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_second11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_second11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_second12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_second13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R21" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">22</div>
</th>
<td class="s11" dir="ltr">Formes géométriques</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_third1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s14">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_third2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_third3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_third4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_third5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">   <?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_third6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_third7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_third8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">

<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_third9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_third10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $sevent_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_third11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_third11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_third12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_third13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R22" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">23</div>
</th>
<td class="s11" dir="ltr">Numération</td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="fourth_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s14">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="fourth_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="fourth_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="fourth_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="fourth_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="fourth_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="fourth_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="fourth_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="fourth_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s14">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="fourth_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $eight_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="fourth_section11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fourth_section11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="fourth_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">240</td>
<td class="s15"><input type="text" name="" class="fourth_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R23" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">24</div>
</th>
<td class="s11" dir="ltr">Opérations</td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="fifth_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="fifth_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="fifth_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>  
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="fifth_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="fifth_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="fifth_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="fifth_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="fifth_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">20</td>
<td class="s13">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="fifth_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="fifth_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">40</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="fifth_section11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="fifth_section11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">80</td>
<td class="s14"><input type="text" name="" class="fifth_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">240</td>
<td class="s15"><input type="text" name="" class="fifth_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R24" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">25</div>
</th>
<td class="s28" dir="ltr">Problèmes</td>
<td class="s17" dir="ltr">20</td>
<td class="s25">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="sixth_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section1sixth_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s18">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="sixth_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">40</td>
<td class="s18">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="sixth_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section3sixth_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">80</td>
<td class="s18"><input type="text" name="" class="sixth_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">20</td>
<td class="s25">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="sixth_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s18">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="sixth_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">40</td>
<td class="s18">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="sixth_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section7sixth_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">80</td>
<td class="s18"><input type="text" name="" class="sixth_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">20</td>
<td class="s25">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="sixth_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s18">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="sixth_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section10sixth_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s17" dir="ltr">40</td>
<td class="s18">
<?php  if($no = $ten_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="sixth_section11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="sixth_section11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">80</td>
<td class="s18"><input type="text" name="" class="sixth_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">240</td>
<td class="s19"><input type="text" name="" class="sixth_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R25" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">26</div>
</th>
<td class="s10" dir="ltr" colspan="24">SCIENCES</td>
</tr>
<tr style="height: 20px">
<th id="645166884R26" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">27</div>
</th>
<td class="s28" dir="ltr">Sciences d'éveil</td>
<td class="s17" dir="ltr">20</td>
<td class="s25">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="seventh_section1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s18">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="seventh_section2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">40</td>
<td class="s18">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="seventh_section3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s17" dir="ltr">80</td>
<td class="s18"><input type="text" name="" class="seventh_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">20</td>
<td class="s25">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="seventh_section5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s18">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="seventh_section6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s17" dir="ltr">40</td>
<td class="s18">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="seventh_section7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s17" dir="ltr">80</td>
<td class="s18"><input type="text" name="" class="seventh_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">20</td>
<td class="s25">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="seventh_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>   
<td class="s18">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="seventh_section10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="seventh_section10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s17" dir="ltr">40</td>
<td class="s18"><input type="text" name="" class="seventh_section11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">80</td>
<td class="s18"><input type="text" name="" class="seventh_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s17" dir="ltr">240</td>
<td class="s19"><input type="text" name="" class="seventh_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R27" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">28</div>
</th>
<td class="s10" dir="ltr" colspan="24">TECHNOLOGIE</td>
</tr>
<tr style="height: 20px">
<th id="645166884R28" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">29</div>
</th>
<td class="s11" dir="ltr">Technologie</td>
<td class="s29" dir="ltr">10</td>
<td class="s11" dir="ltr">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_fourth1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s14">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fourth2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14"><?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_fourth3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td><input type="text" name="" class="tens_section_fourth3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_fourth4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fourth5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fourth6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_fourth7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_fourth8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fourth9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fourth10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twelve_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_fourth11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fourth11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_fourth12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_fourth13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R29" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">30</div>
    </th>
    <td class="s16" dir="ltr">Sous-Total</td>
    <td class="s17" dir="ltr">110</td>
    <td class="s25"><input type="text" name="" class="other_section1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="other_section2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">220</td>
    <td class="s18"><input type="text" name="" class="other_section3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">440</td>
    <td class="s18"><input type="text" name="" class="other_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">110</td>
    <td class="s25"><input type="text" name="" class="other_section5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="other_section6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">220</td>
    <td class="s18"><input type="text" name="" class="other_section7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">440</td>
    <td class="s18"><input type="text" name="" class="other_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">110</td>
    <td class="s25"><input type="text" name="" class="other_section9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="other_section10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">220</td>
    <td class="s18"><input type="text" name="" class="other_section11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">440</td>
    <td class="s18"><input type="text" name="" class="other_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">1320</td>
    <td class="s19"><input type="text" name="" class="other_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R30" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">31</div>
    </th>
    <td class="s7" dir="ltr" colspan="24">DOMAINE DE L &#39;UNIVERS SOCIAL ET ENVIRONNEMENT</td>
</tr>
<tr style="height: 20px">
<th id="645166884R31" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">32</div>
</th>
<td class="s30" dir="ltr">Ed. Civ. &amp;Morale</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_fifth1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>  
<td class="s14">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fifth2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_fifth3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_fifth4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fifth5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fifth6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_fifth7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_fifth8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fifth9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $threteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_fifth10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td></td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $nine_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_fifth11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_fifth11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_fifth12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_fifth13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R32" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">33</div>
</th>
<td class="s11" dir="ltr">Ed. Santé &amp;Env.</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_sixth1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_sixth2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth2tens_section_sixth2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_sixth3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_sixth4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_sixth5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_sixth6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth6tens_section_sixth6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_sixth7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_sixth8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_sixth9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_sixth10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $nineteen_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_sixth11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_sixth11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_sixth12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_sixth13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R33" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">34</div>
    </th>
    <td class="s16" dir="ltr">Sous-Total</td>
    <td class="s17" dir="ltr">20</td>
    <td class="s25"><input type="text" name="" class="eighth_section1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="eighth_section2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s18"><input type="text" name="" class="eighth_section3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="eighth_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">20</td>
    <td class="s25"><input type="text" name="" class="eighth_section5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="eighth_section6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s18"><input type="text" name="" class="eighth_section7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="eighth_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">20</td>
    <td class="s25"><input type="text" name="" class="eighth_section9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="eighth_section10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s18"><input type="text" name="" class="eighth_section11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="eighth_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">240</td>
    <td class="s19"><input type="text" name="" class="eighth_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R34" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">35</div>
    </th>
    <td class="s7" dir="ltr" colspan="24">DOMAINE DES ARTS</td>
</tr>
<tr style="height: 20px">
    <th id="645166884R35" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">36</div>
    </th>
    <td class="s10" dir="ltr" colspan="24">EDUCATION ARTISTIQUE</td>
</tr>
<tr style="height: 20px">
<th id="645166884R36" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">37</div>
</th>
<td class="s11" dir="ltr">Arts plastiques</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_seven1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven1tens_section_seven1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_seven2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_seven3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_seven4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_seven5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_seven6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_seven7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_seven8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">

<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_seven9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td> 
<td class="s14">
<?php  if($no = $twentytwo_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_seven10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?></td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $eleven_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_seven11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_seven11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_seven12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_seven13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R37" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">38</div>
</th>
<td class="s11" dir="ltr">Arts dramatiques</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_eight1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eight2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_eight3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight3tens_section_eight3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_eight4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eight5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eight6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_eight7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_eight8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">

<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="eight_section9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eight10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentythree_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_eight11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eight11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_eight12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_eight13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R38" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">39</div>
    </th>
    <td class="s16" dir="ltr">Sous-Total</td>
    <td class="s17" dir="ltr">20</td>
    <td class="s25"><input type="text" name="" class="nineth_section1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="nineth_section2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s18"><input type="text" name="" class="nineth_section3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="nineth_section4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">20</td>
    <td class="s25"><input type="text" name="" class="nineth_section5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="nineth_section6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s18"><input type="text" name="" class="nineth_section7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="nineth_section8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">20</td>
    <td class="s25"><input type="text" name="" class="nineth_section9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s18"><input type="text" name="" class="nineth_section10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">40</td>
    <td class="s18"><input type="text" name="" class="nineth_section11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">80</td>
    <td class="s18"><input type="text" name="" class="nineth_section12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
    <td class="s17" dir="ltr">240</td>
    <td class="s19"><input type="text" name="" class="nineth_section13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
    <th id="645166884R39" style="height: 20px;" class="row-headers-background">
        <div class="row-header-wrapper" style="line-height: 20px">40</div>
    </th>
    <td class="s7" dir="ltr" colspan="24">DOMAINE DU DEVELOPPEMENT PERSONNEL</td>
</tr>

<tr style="height: 20px">
<th id="645166884R40" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">41</div>
</th>
<td class="s11" dir="ltr">Ed. phys. &amp;sports</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_nine1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_nine2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_nine3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_nine4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_nine5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine5tens_section_nine5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_nine6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_nine7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_nine8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">

<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_nine9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_nine10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>

</td>
<td class="s12" dir="ltr">20</td>
<td class="s14"> <?php  if($no = $twentyfive_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_nine11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_nine11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_nine12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_nine13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R41" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">42</div>
</th>
<td class="s11" dir="ltr">Init. Trav. Prod.</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_ten1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_ten2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_ten3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_ten4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_ten5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s14">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_ten6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_ten7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_ten8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_ten9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_ten10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentyfour_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_ten11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_ten11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_ten12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_ten13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
<tr style="height: 20px">
<th id="645166884R42" style="height: 20px;" class="row-headers-background">
<div class="row-header-wrapper" style="line-height: 20px">43</div>
</th>
<td class="s11" dir="ltr">Religion (1)</td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "1ère PERIODE"){ ?>
<input type="text" name="" class="tens_section_eleven1" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven1"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s14">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "2ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eleven2" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven2"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "PREMIER TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_eleven3" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven3"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_eleven4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "3ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eleven5" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven5"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "4ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eleven6" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven6"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>
<td class="s14">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "DEUXIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_eleven7" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven7"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_eleven8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">10</td>
<td class="s13">
<?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "5ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eleven9" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven9"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?> 
</td>
<td class="s14"><?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "6ème PERIODE"){ ?>
<input type="text" name="" class="tens_section_eleven10" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven10"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">20</td>

<td class="s14"> <?php  if($no = $twentysix_period){ ?>
<?php foreach($no as $key => $value) { if($value->period_name == "manual exams" && $value->semester_name == "TROISIEME TRIMESTRE"){ ?>
<input type="text" name="" class="tens_section_eleven11" value="<?php echo $value->max_marks; ?>" style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } } } else{ ?>
<input type="text" name="" class="tens_section_eleven11"  style="font-weight: bold;width: 38px;border-color: transparent;border: none;">
<?php } ?>
</td>
<td class="s12" dir="ltr">40</td>
<td class="s14"><input type="text" name="" class="tens_section_eleven12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
<td class="s12" dir="ltr">120</td>
<td class="s15"><input type="text" name="" class="tens_section_eleven13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
</tr>
            <tr style="height: 20px">
                <th id="645166884R43" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">44</div>
                </th>
                <td class="s31" dir="ltr">Sous-Total</td>
                <td class="s12" dir="ltr">30</td>
                <td class="s13"><input type="text" name="" class="thirty_section_third1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s14"><input type="text" name="" class="thirty_section_third2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">60</td>
                <td class="s14"><input type="text" name="" class="thirty_section_third3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">120</td>
                <td class="s14"><input type="text" name="" class="thirty_section_third4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">30</td>
                <td class="s13"><input type="text" name="" class="thirty_section_third5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s14"><input type="text" name="" class="thirty_section_third6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">60</td>
                <td class="s14"><input type="text" name="" class="thirty_section_third7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">120</td>
                <td class="s14"><input type="text" name="" class="thirty_section_third8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">30</td>
                <td class="s13"><input type="text" name="" class="thirty_section_third9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s14"><input type="text" name="" class="thirty_section_third10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">60</td>
                <td class="s14"><input type="text" name="" class="thirty_section_third11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">120</td>
                <td class="s14"><input type="text" name="" class="thirty_section_third12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">360</td>
                <td class="s15"><input type="text" name="" class="thirty_section_third13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R44" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">45</div>
                </th>
                <td class="s32" dir="ltr">Maxima généraux</td>
                <td class="s12" dir="ltr">280</td>
                <td class="s13"><input type="text" name="" class="other_section_one1"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s14"><input type="text" name="" class="other_section_one2"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">560</td>
                <td class="s14"><input type="text" name="" class="other_section_one3"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">1120</td>
                <td class="s14"><input type="text" name="" class="other_section_one4"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">280</td>
                <td class="s13"><input type="text" name="" class="other_section_one5"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s14"><input type="text" name="" class="other_section_one6"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">560</td>
                <td class="s14"><input type="text" name="" class="other_section_one7"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">1120</td>
                <td class="s14"><input type="text" name="" class="other_section_one8"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">280</td>
                <td class="s13"><input type="text" name="" class="other_section_one9"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s14"><input type="text" name="" class="other_section_one10"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">560</td>
                <td class="s14"><input type="text" name="" class="other_section_one11"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">1120</td>
                <td class="s14"><input type="text" name="" class="other_section_one12"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
                <td class="s12" dir="ltr">3360</td>
                <td class="s15"><input type="text" name="" class="other_section_one13"  style="font-weight: bold; width: 38px; border: none; color: red;"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R45" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">46</div>
                </th>
                <td class="s11">POURCENTAGE</td>
                <td class="s33"></td>
                <td class="s13"><input type="text" value="" class="xy1" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="" class="xy2" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="" class="xy3" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="" class="xy4" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s13"><input type="text" value="" class="xy5" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="" class="xy6" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="" class="xy7" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="" class="xy8" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s14"><input type="text" value="" class="xy9" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="" class="xy10" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="" class="xy11" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="" class="xy12" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
                <td class="s36"></td>
                <td class="s15"><input type="text" value="" class="xy13" style="font-weight: bold;border: none;width: 33px;height: 28px;overflow: visible;"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R46" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">47</div>
                </th>
                <?php $plce = explode(",", $report_marks->place); ?>
                <td class="s11">PLACE/NBRE ÉLÈVES</td>
                <td class="s33"></td>
                <td class="s13"><input type="text"  name="place[]" value="<?= $plce[0] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[1] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[2] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[3] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s13"><input type="text"  name="place[]" value="<?= $plce[4] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[5] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[6] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[7] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[8] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[9] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[10] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text"  name="place[]" value="<?= $plce[11] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s36"></td>
                <td class="s15"><input type="text"  name="place[]" value="<?= $plce[12] ?>" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R47" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">48</div>
                </th>
                <?php $appl = explode(",", $report_marks->application); ?>
                <td class="s11">APPLICATION</td>
                <td class="s33"></td>
                <td class="s13"><input type="text" value="<?= $appl[0] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="<?= $appl[1] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $appl[2] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $appl[3] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s13"><input type="text" value="<?= $appl[4] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="<?= $appl[5] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $appl[6] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $appl[7] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s14"><input type="text" value="<?= $appl[8] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="<?= $appl[9] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $appl[10] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $appl[11] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s36"></td>
                <td class="s15"><input type="text" value="<?= $appl[12] ?>" name="applictn[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R48" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">49</div>
                </th>
                <?php  $cond = explode(",", $report_marks->conduite); ?>
                <td class="s11">CONDUITE</td>
                <td class="s33"></td>
                <td class="s13"><input type="text" value="<?= $cond[0] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="<?= $cond[1] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $cond[2] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $cond[3] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s13"><input type="text" value="<?= $cond[4] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="<?= $cond[5] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $cond[6] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $cond[7] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s35"></td>
                <td class="s14"><input type="text" value="<?= $cond[8] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s14"><input type="text" value="<?= $cond[9] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $cond[10] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s34"></td>
                <td class="s14"><input type="text" value="<?= $cond[11] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
                <td class="s36"></td>
                <td class="s15"><input type="text" value="<?= $cond[12] ?>" name="condute[]" style="font-size:13px; font-weight: bold;border: none;width: 31px;height: 28px;overflow: visible;"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R49" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">50</div>
                </th>
                <td class="s11" dir="ltr">SIGNAT. DE L &#39;INST.</td>
                <td class="s33"></td>
                <td class="s37"></td>
                <td class="s38"></td>
                <td class="s39"></td>
                <td class="s37"></td>
                <td class="s40"></td>
                <td class="s41"></td>
                <td class="s40"></td>
                <td class="s41"></td>
                <td class="s41"></td>
                <td class="s40"></td>
                <td class="s41"></td>
                <td class="s42"></td>
                <td class="s43"></td>
                <td class="s42"></td>
                <td class="s41"></td>
                <td class="s41"></td>
                <td class="s40"></td>
                <td class="s41"></td>
                <td class="s40"></td>
                <td class="s41"></td>
                <td class="s44"></td>
                <td class="s45"></td>
            </tr>
            <tr style="height: 20px">
                <th id="645166884R50" style="height: 20px;" class="row-headers-background">
                    <div class="row-header-wrapper" style="line-height: 20px">51</div>
                </th>
                <td class="s28" dir="ltr">SIGNAT. DU RESP.</td>
                <td class="s46"></td>
                <td class="s47"></td>
                <td class="s48"></td>
                <td class="s49"></td>
                <td class="s50"></td>
                <td class="s51"></td>
                <td class="s21"></td>
                <td class="s52"></td>
                <td class="s21"></td>
                <td class="s21"></td>
                <td class="s53"></td>
                <td class="s54"></td>
                <td class="s53"></td>
                <td class="s54"></td>
                <td class="s53"></td>
                <td class="s21"></td>
                <td class="s21"></td>
                <td class="s52"></td>
                <td class="s21"></td>
                <td class="s52"></td>
                <td class="s21"></td>
                <td class="s55"></td>
                <td class="s24"></td>
            </tr>
            
        </tbody>
  </table>
</div>
        </div>
    </div>
</div>

 

 <div class="container">
     <div class="row">
         <div class="col-md-12 text-right">
              Fait à <input type="text" name="" value=""   style="font-weight: bold;border-bottom: 1px dashed #333 !important;  width: 200px;border-color: transparent;border: none;height: 38px;">,le<input type="text" name="" value=""   style="font-weight: bold;border-bottom: 1px dashed #333 !important;  width: 40px;border-color: transparent;border: none;height: 38px;">/<input type="text" name="" value=""   style="font-weight: bold;border-bottom: 1px dashed #333 !important;  width: 40px;border-color: transparent;border: none;height: 38px;">/<input type="text" name="" value=""   style="font-weight: bold;border-bottom: 1px dashed #333 !important;  width: 40px;border-color: transparent;border: none;height: 38px;">
         </div>
         <div class="col-md-12"> - L’élève passe dans la classe supérieure (1)<br> - L’élève double sa classe (1) </div>
         <div class="col-md-12 text-right">  LE CHEF D'ÉTABLISSEMENT</div>
            <div class="col-md-12 text-right" style="margin-top: 20px;"> NOM & SIGNATURE</div>
                        <div class="col-md-12 text-center"  style="margin-top: 20px;">   SCEAU DE L'ÉCOLE</div>
                        <div class="col-md-12"  style="margin-top: 20px;">(1) Biffer la mention inutile<br></div>
                        <div class="col-md-6"> Note importante : Le bulletin est sans
            valeur s’il est raturé ou surchargé. </span></div>
                        <div class="col-md-6 text-right"><span
            style="font-size:12pt;font-style:italic;">IGE/P.S/005<br></span></div>
     </div>
 </div>
                        </div>
                    </div>
                    
                </div>
            </div>









                        </div>

                    </div>

                    

                </div>

            </div>

        </div>

    </div>

</div>   
<?php echo $this->Form->end(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript">
    setInterval(
        function(){ 
        var firstxy = parseInt($('.first_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.first_section1').css({'color':'blue'});
        }
        else
        {
        $('.first_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.first_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.first_section2').css({'color':'blue'});
        }
        else
        {
        $('.first_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.first_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.first_section3').css({'color':'blue'});
        }
        else
        {
        $('.first_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.first_section1').val());
           var ab = parseInt($('.first_section2').val());
           var yz = parseInt($('.first_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.first_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.first_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.first_section5').css({'color':'blue'});
        }
        else
        {
        $('.first_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.first_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.first_section6').css({'color':'blue'});
        }
        else
        {
        $('.first_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.first_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.first_section7').css({'color':'blue'});
        }
        else
        {
        $('.first_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.first_section5').val());
           var ab = parseInt($('.first_section6').val());
           var yz = parseInt($('.first_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.first_section8').val(xy + yz + ab);
        var firstxy = parseInt($('.first_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.first_section9').css({'color':'blue'});
        }
        else
        {
        $('.first_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.first_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.first_section10').css({'color':'blue'});
        }
        else
        {
        $('.first_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.first_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.first_section11').css({'color':'blue'});
        }
        else
        {
        $('.first_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.first_section9').val());
           var ab = parseInt($('.first_section10').val());
           var yz = parseInt($('.first_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.first_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.first_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.first_section13').css({'color':'blue'});
        }
        else
        {
        $('.first_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.first_section4').val());
           var ab = parseInt($('.first_section8').val());
           var yz = parseInt($('.first_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.first_section13').val(xy + yz + ab);

    // second line
    
     var firstxy = parseInt($('.second_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.second_section1').css({'color':'blue'});
        }
        else
        {
        $('.second_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.second_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.second_section2').css({'color':'blue'});
        }
        else
        {
        $('.second_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.second_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.second_section3').css({'color':'blue'});
        }
        else
        {
        $('.second_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.second_section1').val());
           var ab = parseInt($('.second_section2').val());
           var yz = parseInt($('.second_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.second_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.second_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.second_section5').css({'color':'blue'});
        }
        else
        {
        $('.second_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.second_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.second_section6').css({'color':'blue'});
        }
        else
        {
        $('.second_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.second_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.second_section7').css({'color':'blue'});
        }
        else
        {
        $('.second_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.second_section5').val());
           var ab = parseInt($('.second_section6').val());
           var yz = parseInt($('.second_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.second_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.second_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.second_section9').css({'color':'blue'});
        }
        else
        {
        $('.second_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.second_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.second_section10').css({'color':'blue'});
        }
        else
        {
        $('.second_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.second_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.second_section11').css({'color':'blue'});
        }
        else
        {
        $('.second_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.second_section9').val());
           var ab = parseInt($('.second_section10').val());
           var yz = parseInt($('.second_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.second_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.second_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.second_section13').css({'color':'blue'});
        }
        else
        {
        $('.second_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.second_section4').val());
           var ab = parseInt($('.second_section8').val());
           var yz = parseInt($('.second_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.second_section13').val(xy + yz + ab);

    // third line
    
     var firstxy = parseInt($('.third_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.third_section1').css({'color':'blue'});
        }
        else
        {
        $('.third_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.third_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.third_section2').css({'color':'blue'});
        }
        else
        {
        $('.third_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.third_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.third_section3').css({'color':'blue'});
        }
        else
        {
        $('.third_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.third_section1').val());
           var ab = parseInt($('.third_section2').val());
           var yz = parseInt($('.third_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.third_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.third_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.third_section5').css({'color':'blue'});
        }
        else
        {
        $('.third_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.third_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.third_section6').css({'color':'blue'});
        }
        else
        {
        $('.third_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.third_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.third_section7').css({'color':'blue'});
        }
        else
        {
        $('.third_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.third_section5').val());
           var ab = parseInt($('.third_section6').val());
           var yz = parseInt($('.third_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.third_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.third_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.third_section9').css({'color':'blue'});
        }
        else
        {
        $('.third_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.third_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.third_section10').css({'color':'blue'});
        }
        else
        {
        $('.third_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.third_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.third_section11').css({'color':'blue'});
        }
        else
        {
        $('.third_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.third_section9').val());
           var ab = parseInt($('.third_section10').val());
           var yz = parseInt($('.third_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.third_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.third_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.third_section13').css({'color':'blue'});
        }
        else
        {
        $('.third_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.third_section4').val());
           var ab = parseInt($('.third_section8').val());
           var yz = parseInt($('.third_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.third_section13').val(xy + yz + ab);

// fourth line

   var firstxy = parseInt($('.fourth_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fourth_section1').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fourth_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fourth_section2').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fourth_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.fourth_section3').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.fourth_section1').val());
           var ab = parseInt($('.fourth_section2').val());
           var yz = parseInt($('.fourth_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fourth_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.fourth_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fourth_section5').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fourth_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fourth_section6').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fourth_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.fourth_section7').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.fourth_section5').val());
           var ab = parseInt($('.fourth_section6').val());
           var yz = parseInt($('.fourth_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fourth_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.fourth_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fourth_section9').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fourth_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fourth_section10').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fourth_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.fourth_section11').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.fourth_section9').val());
           var ab = parseInt($('.fourth_section10').val());
           var yz = parseInt($('.fourth_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fourth_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.fourth_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.fourth_section13').css({'color':'blue'});
        }
        else
        {
        $('.fourth_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.fourth_section4').val());
           var ab = parseInt($('.fourth_section8').val());
           var yz = parseInt($('.fourth_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fourth_section13').val(xy + yz + ab);

    //fifth section
    
      var firstxy = parseInt($('.fifth_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fifth_section1').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fifth_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fifth_section2').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fifth_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.fifth_section3').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.fifth_section1').val());
           var ab = parseInt($('.fifth_section2').val());
           var yz = parseInt($('.fifth_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fifth_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.fifth_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fifth_section5').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fifth_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fifth_section6').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fifth_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.fifth_section7').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.fifth_section5').val());
           var ab = parseInt($('.fifth_section6').val());
           var yz = parseInt($('.fifth_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fifth_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.fifth_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fifth_section9').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fifth_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.fifth_section10').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.fifth_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.fifth_section11').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.fifth_section9').val());
           var ab = parseInt($('.fifth_section10').val());
           var yz = parseInt($('.fifth_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fifth_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.fifth_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.fifth_section13').css({'color':'blue'});
        }
        else
        {
        $('.fifth_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.fifth_section4').val());
           var ab = parseInt($('.fifth_section8').val());
           var yz = parseInt($('.fifth_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.fifth_section13').val(xy + yz + ab);

// sixth section


      var firstxy = parseInt($('.sixth_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.sixth_section1').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.sixth_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.sixth_section2').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.sixth_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.sixth_section3').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.sixth_section1').val());
           var ab = parseInt($('.sixth_section2').val());
           var yz = parseInt($('.sixth_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.sixth_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.sixth_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.sixth_section5').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.sixth_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.sixth_section6').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.sixth_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.sixth_section7').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.sixth_section5').val());
           var ab = parseInt($('.sixth_section6').val());
           var yz = parseInt($('.sixth_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.sixth_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.sixth_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.sixth_section9').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.sixth_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.sixth_section10').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.sixth_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.sixth_section11').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.sixth_section9').val());
           var ab = parseInt($('.sixth_section10').val());
           var yz = parseInt($('.sixth_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.sixth_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.sixth_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.sixth_section13').css({'color':'blue'});
        }
        else
        {
        $('.sixth_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.sixth_section4').val());
           var ab = parseInt($('.sixth_section8').val());
           var yz = parseInt($('.sixth_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.sixth_section13').val(xy + yz + ab);
           
           
        //   seventh section
        
        
      var firstxy = parseInt($('.seventh_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.seventh_section1').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.seventh_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.seventh_section2').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.seventh_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.seventh_section3').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.seventh_section1').val());
           var ab = parseInt($('.seventh_section2').val());
           var yz = parseInt($('.seventh_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.seventh_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.seventh_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.seventh_section5').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.seventh_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.seventh_section6').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.seventh_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.seventh_section7').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.seventh_section5').val());
           var ab = parseInt($('.seventh_section6').val());
           var yz = parseInt($('.seventh_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.seventh_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.seventh_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.seventh_section9').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.seventh_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.seventh_section10').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.seventh_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.seventh_section11').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.seventh_section9').val());
           var ab = parseInt($('.seventh_section10').val());
           var yz = parseInt($('.seventh_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.seventh_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.seventh_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.seventh_section13').css({'color':'blue'});
        }
        else
        {
        $('.seventh_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.seventh_section4').val());
           var ab = parseInt($('.seventh_section8').val());
           var yz = parseInt($('.seventh_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.seventh_section13').val(xy + yz + ab);
           
        //   eight Section
        
         var firstxy = parseInt($('.eighth_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.eighth_section1').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.eighth_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.eighth_section2').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.eighth_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.eighth_section3').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.eighth_section1').val());
           var ab = parseInt($('.eighth_section2').val());
           var yz = parseInt($('.eighth_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.eighth_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.eighth_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.eighth_section5').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.eighth_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.eighth_section6').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.eighth_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.eighth_section7').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.eighth_section5').val());
           var ab = parseInt($('.eighth_section6').val());
           var yz = parseInt($('.eighth_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.eighth_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.eighth_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.eighth_section9').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.eighth_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.eighth_section10').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.eighth_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.eighth_section11').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.eighth_section9').val());
           var ab = parseInt($('.eighth_section10').val());
           var yz = parseInt($('.eighth_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.eighth_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.eighth_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.eighth_section13').css({'color':'blue'});
        }
        else
        {
        $('.eighth_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.eighth_section4').val());
           var ab = parseInt($('.eighth_section8').val());
           var yz = parseInt($('.eighth_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.eighth_section13').val(xy + yz + ab);
           
        //  ninth section
        
          var firstxy = parseInt($('.nineth_section1').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.nineth_section1').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.nineth_section2').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.nineth_section2').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.nineth_section3').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.nineth_section3').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.nineth_section1').val());
           var ab = parseInt($('.nineth_section2').val());
           var yz = parseInt($('.nineth_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.nineth_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.nineth_section5').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.nineth_section5').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.nineth_section6').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.nineth_section6').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.nineth_section7').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.nineth_section7').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.nineth_section5').val());
           var ab = parseInt($('.nineth_section6').val());
           var yz = parseInt($('.nineth_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.nineth_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.nineth_section9').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.nineth_section9').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.nineth_section10').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.nineth_section10').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.nineth_section11').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.nineth_section11').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.nineth_section9').val());
           var ab = parseInt($('.nineth_section10').val());
           var yz = parseInt($('.nineth_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.nineth_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.nineth_section13').val());
        var firstp = firstxy / 240 * 100;
        if(firstp >= 50) {
        $('.nineth_section13').css({'color':'blue'});
        }
        else
        {
        $('.nineth_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.nineth_section4').val());
           var ab = parseInt($('.nineth_section8').val());
           var yz = parseInt($('.nineth_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.nineth_section13').val(xy + yz + ab);
           
           
        //   ten's section
        
             
          var firstxy = parseInt($('.tens_section_one1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_one1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_one2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_one2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_one3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_one3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_one1').val());
           var ab = parseInt($('.tens_section_one2').val());
           var yz = parseInt($('.tens_section_one3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_one4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_one5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_one5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_one6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_one6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_one7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_one7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_one5').val());
           var ab = parseInt($('.tens_section_one6').val());
           var yz = parseInt($('.tens_section_one7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_one8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_one9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_one9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_one10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_one10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_one11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_one11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_one9').val());
           var ab = parseInt($('.tens_section_one10').val());
           var yz = parseInt($('.tens_section_one11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_one12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_one13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_one13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_one13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_one4').val());
           var ab = parseInt($('.tens_section_one8').val());
           var yz = parseInt($('.tens_section_one12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_one13').val(xy + yz + ab);
           
           
        //   ten second
        
               var firstxy = parseInt($('.tens_section_second1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_second1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_second2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_second2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_second3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_second3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_second1').val());
           var ab = parseInt($('.tens_section_second2').val());
           var yz = parseInt($('.tens_section_second3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_second4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_second5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_second5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_second6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_second6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_second7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_second7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_second5').val());
           var ab = parseInt($('.tens_section_second6').val());
           var yz = parseInt($('.tens_section_second7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_second8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_second9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_second9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_second10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_second10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_second11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_second11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_second9').val());
           var ab = parseInt($('.tens_section_second10').val());
           var yz = parseInt($('.tens_section_second11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_second12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_second13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_second13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_second13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_second4').val());
           var ab = parseInt($('.tens_section_second8').val());
           var yz = parseInt($('.tens_section_second12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_second13').val(xy + yz + ab);
           
        //   ten third
        
             var firstxy = parseInt($('.tens_section_third1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_third1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_third2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_third2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_third3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_third3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_third1').val());
           var ab = parseInt($('.tens_section_third2').val());
           var yz = parseInt($('.tens_section_third3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_third4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_third5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_third5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_third6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_third6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_third7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_third7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_third5').val());
           var ab = parseInt($('.tens_section_third6').val());
           var yz = parseInt($('.tens_section_third7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_third8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_third9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_third9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_third10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_third10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_third11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_third11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_third9').val());
           var ab = parseInt($('.tens_section_third10').val());
           var yz = parseInt($('.tens_section_third11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_third12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_third13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_third13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_third13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_third4').val());
           var ab = parseInt($('.tens_section_third8').val());
           var yz = parseInt($('.tens_section_third12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_third13').val(xy + yz + ab);
           
    // ten fourth
    
     
             var firstxy = parseInt($('.tens_section_fourth1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fourth2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fourth3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_fourth1').val());
           var ab = parseInt($('.tens_section_fourth2').val());
           var yz = parseInt($('.tens_section_fourth3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fourth4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_fourth5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fourth6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fourth7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_fourth5').val());
           var ab = parseInt($('.tens_section_fourth6').val());
           var yz = parseInt($('.tens_section_fourth7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fourth8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_fourth9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fourth10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fourth11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_fourth9').val());
           var ab = parseInt($('.tens_section_fourth10').val());
           var yz = parseInt($('.tens_section_fourth11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fourth12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_fourth13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_fourth13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fourth13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_fourth4').val());
           var ab = parseInt($('.tens_section_fourth8').val());
           var yz = parseInt($('.tens_section_fourth12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fourth13').val(xy + yz + ab);
        //   ten fifth
        
        
             var firstxy = parseInt($('.tens_section_fifth1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fifth2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fifth3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_fifth1').val());
           var ab = parseInt($('.tens_section_fifth2').val());
           var yz = parseInt($('.tens_section_fifth3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fifth4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_fifth5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fifth6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fifth7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_fifth5').val());
           var ab = parseInt($('.tens_section_fifth6').val());
           var yz = parseInt($('.tens_section_fifth7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fifth8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_fifth9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fifth10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_fifth11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_fifth9').val());
           var ab = parseInt($('.tens_section_fifth10').val());
           var yz = parseInt($('.tens_section_fifth11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fifth12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_fifth13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_fifth13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_fifth13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_fifth4').val());
           var ab = parseInt($('.tens_section_fifth8').val());
           var yz = parseInt($('.tens_section_fifth12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_fifth13').val(xy + yz + ab);
           
        //  ten sixth
        
        
             var firstxy = parseInt($('.tens_section_sixth1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_sixth2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_sixth3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_sixth1').val());
           var ab = parseInt($('.tens_section_sixth2').val());
           var yz = parseInt($('.tens_section_sixth3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_sixth4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_sixth5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_sixth6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_sixth7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_sixth5').val());
           var ab = parseInt($('.tens_section_sixth6').val());
           var yz = parseInt($('.tens_section_sixth7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_sixth8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_sixth9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_sixth10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_sixth11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_sixth9').val());
           var ab = parseInt($('.tens_section_sixth10').val());
           var yz = parseInt($('.tens_section_sixth11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_sixth12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_sixth13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_sixth13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_sixth13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_sixth4').val());
           var ab = parseInt($('.tens_section_sixth8').val());
           var yz = parseInt($('.tens_section_sixth12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_sixth13').val(xy + yz + ab);
           
        //   ten seven
        
         
             var firstxy = parseInt($('.tens_section_seven1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_seven2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_seven3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_seven1').val());
           var ab = parseInt($('.tens_section_seven2').val());
           var yz = parseInt($('.tens_section_seven3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_seven4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_seven5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_seven6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_seven7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_seven5').val());
           var ab = parseInt($('.tens_section_seven6').val());
           var yz = parseInt($('.tens_section_seven7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_seven8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_seven9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_seven10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_seven11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_seven9').val());
           var ab = parseInt($('.tens_section_seven10').val());
           var yz = parseInt($('.tens_section_seven11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_seven12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_seven13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_seven13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_seven13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_seven4').val());
           var ab = parseInt($('.tens_section_seven8').val());
           var yz = parseInt($('.tens_section_seven12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_seven13').val(xy + yz + ab);
           
           
        //   ten eight
        
             var firstxy = parseInt($('.tens_section_eight1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eight2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eight3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_eight1').val());
           var ab = parseInt($('.tens_section_eight2').val());
           var yz = parseInt($('.tens_section_eight3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eight4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_eight5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eight6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eight7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight7').css({'color':'Red'});
        }
        
           var xy = parseInt($('.tens_section_eight5').val());
           var ab = parseInt($('.tens_section_eight6').val());
           var yz = parseInt($('.tens_section_eight7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eight8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_eight9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eight10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eight11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight11').css({'color':'Red'});
        }
           var xy = parseInt($('.eight_section9').val());
           var ab = parseInt($('.tens_section_eight10').val());
           var yz = parseInt($('.tens_section_eight11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eight12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_eight13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_eight13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eight13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_eight4').val());
           var ab = parseInt($('.tens_section_eight8').val());
           var yz = parseInt($('.tens_section_eight12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eight13').val(xy + yz + ab);
           
           
        //   ten nine
        
             var firstxy = parseInt($('.tens_section_nine1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_nine2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_nine3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_nine1').val());
           var ab = parseInt($('.tens_section_nine2').val());
           var yz = parseInt($('.tens_section_nine3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_nine4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_nine5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_nine6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_nine7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_nine5').val());
           var ab = parseInt($('.tens_section_nine6').val());
           var yz = parseInt($('.tens_section_nine7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_nine8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_nine9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_nine10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_nine11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_nine9').val());
           var ab = parseInt($('.tens_section_nine10').val());
           var yz = parseInt($('.tens_section_nine11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_nine12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_nine13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_nine13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_nine13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_nine4').val());
           var ab = parseInt($('.tens_section_nine8').val());
           var yz = parseInt($('.tens_section_nine12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_nine13').val(xy + yz + ab);
           
        //   ten ten
          
             var firstxy = parseInt($('.tens_section_ten1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_ten2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_ten3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_ten1').val());
           var ab = parseInt($('.tens_section_ten2').val());
           var yz = parseInt($('.tens_section_ten3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_ten4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_ten5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_ten6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_ten7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_ten5').val());
           var ab = parseInt($('.tens_section_ten6').val());
           var yz = parseInt($('.tens_section_ten7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_ten8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_ten9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_ten10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_ten11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_ten9').val());
           var ab = parseInt($('.tens_section_ten10').val());
           var yz = parseInt($('.tens_section_ten11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_ten12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_ten13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_ten13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_ten13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_ten4').val());
           var ab = parseInt($('.tens_section_ten8').val());
           var yz = parseInt($('.tens_section_ten12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_ten13').val(xy + yz + ab);
           
        //   ten eleven
        
            
             var firstxy = parseInt($('.tens_section_eleven1').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven1').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eleven2').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven2').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eleven3').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven3').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven3').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_eleven1').val());
           var ab = parseInt($('.tens_section_eleven2').val());
           var yz = parseInt($('.tens_section_eleven3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eleven4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_eleven5').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven5').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eleven6').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven6').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eleven7').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven7').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven7').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_eleven5').val());
           var ab = parseInt($('.tens_section_eleven6').val());
           var yz = parseInt($('.tens_section_eleven7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eleven8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.tens_section_eleven9').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven9').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eleven10').val());
        var firstp = firstxy / 10 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven10').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.tens_section_eleven11').val());
        var firstp = firstxy / 20 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven11').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven11').css({'color':'Red'});
        }
           var xy = parseInt($('.tens_section_eleven9').val());
           var ab = parseInt($('.tens_section_eleven10').val());
           var yz = parseInt($('.tens_section_eleven11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eleven12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.tens_section_eleven13').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.tens_section_eleven13').css({'color':'blue'});
        }
        else
        {
        $('.tens_section_eleven13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.tens_section_eleven4').val());
           var ab = parseInt($('.tens_section_eleven8').val());
           var yz = parseInt($('.tens_section_eleven12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.tens_section_eleven13').val(xy + yz + ab);
           
        //   thirty sectionone
        
              
             var firstxy = parseInt($('.thirty_section_one1').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one1').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_one2').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one2').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_one3').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one3').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one3').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_one1').val());
           var ab = parseInt($('.thirty_section_one2').val());
           var yz = parseInt($('.thirty_section_one3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_one4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.thirty_section_one5').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one5').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_one6').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one6').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_one7').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one7').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one7').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_one5').val());
           var ab = parseInt($('.thirty_section_one6').val());
           var yz = parseInt($('.thirty_section_one7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_one8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.thirty_section_one9').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one9').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_one10').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one10').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_one11').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one11').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one11').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_one9').val());
           var ab = parseInt($('.thirty_section_one10').val());
           var yz = parseInt($('.thirty_section_one11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_one12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.thirty_section_one13').val());
        var firstp = firstxy / 360 * 100;
        if(firstp >= 50) {
        $('.thirty_section_one13').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_one13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.thirty_section_one4').val());
           var ab = parseInt($('.thirty_section_one8').val());
           var yz = parseInt($('.thirty_section_one12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_one13').val(xy + yz + ab);
           
           
        // thirty section second
        
        
             var firstxy = parseInt($('.thirty_section_second1').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second1').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_second2').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second2').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_second3').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second3').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second3').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_second1').val());
           var ab = parseInt($('.thirty_section_second2').val());
           var yz = parseInt($('.thirty_section_second3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_second4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.thirty_section_second5').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second5').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_second6').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second6').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_second7').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second7').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second7').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_second5').val());
           var ab = parseInt($('.thirty_section_second6').val());
           var yz = parseInt($('.thirty_section_second7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_second8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.thirty_section_second9').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second9').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_second10').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second10').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_second11').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second11').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second11').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_second9').val());
           var ab = parseInt($('.thirty_section_second10').val());
           var yz = parseInt($('.thirty_section_second11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_second12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.thirty_section_second13').val());
        var firstp = firstxy / 360 * 100;
        if(firstp >= 50) {
        $('.thirty_section_second13').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_second13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.thirty_section_second4').val());
           var ab = parseInt($('.thirty_section_second8').val());
           var yz = parseInt($('.thirty_section_second12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_second13').val(xy + yz + ab);
           
           
               
        // thirty section third
        
        
             var firstxy = parseInt($('.thirty_section_third1').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third1').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_third2').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third2').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_third3').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third3').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third3').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_third1').val());
           var ab = parseInt($('.thirty_section_third2').val());
           var yz = parseInt($('.thirty_section_third3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_third4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.thirty_section_third5').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third5').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_third6').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third6').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_third7').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third7').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third7').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_third5').val());
           var ab = parseInt($('.thirty_section_third6').val());
           var yz = parseInt($('.thirty_section_third7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_third8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.thirty_section_third9').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third9').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_third10').val());
        var firstp = firstxy / 30 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third10').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.thirty_section_third11').val());
        var firstp = firstxy / 60 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third11').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third11').css({'color':'Red'});
        }
           var xy = parseInt($('.thirty_section_third9').val());
           var ab = parseInt($('.thirty_section_third10').val());
           var yz = parseInt($('.thirty_section_third11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_third12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.thirty_section_third13').val());
        var firstp = firstxy / 360 * 100;
        if(firstp >= 50) {
        $('.thirty_section_third13').css({'color':'blue'});
        }
        else
        {
        $('.thirty_section_third13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.thirty_section_third4').val());
           var ab = parseInt($('.thirty_section_third8').val());
           var yz = parseInt($('.thirty_section_third12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_third13').val(xy + yz + ab);
           
           
               
        // forty section 
        
        
             var firstxy = parseInt($('.forty_section1').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.forty_section1').css({'color':'blue'});
        }
        else
        {
        $('.forty_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.forty_section2').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.forty_section2').css({'color':'blue'});
        }
        else
        {
        $('.forty_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.forty_section3').val());
        var firstp = firstxy / 80 * 100;
        if(firstp >= 50) {
        $('.forty_section3').css({'color':'blue'});
        }
        else
        {
        $('.forty_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.forty_section1').val());
           var ab = parseInt($('.forty_section2').val());
           var yz = parseInt($('.forty_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.forty_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.forty_section5').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.forty_section5').css({'color':'blue'});
        }
        else
        {
        $('.forty_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.forty_section6').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.forty_section6').css({'color':'blue'});
        }
        else
        {
        $('.forty_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.forty_section7').val());
        var firstp = firstxy / 80 * 100;
        if(firstp >= 50) {
        $('.forty_section7').css({'color':'blue'});
        }
        else
        {
        $('.forty_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.forty_section5').val());
           var ab = parseInt($('.forty_section6').val());
           var yz = parseInt($('.forty_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.forty_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.forty_section9').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.forty_section9').css({'color':'blue'});
        }
        else
        {
        $('.forty_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.forty_section10').val());
        var firstp = firstxy / 40 * 100;
        if(firstp >= 50) {
        $('.forty_section10').css({'color':'blue'});
        }
        else
        {
        $('.forty_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.forty_section11').val());
        var firstp = firstxy / 80 * 100;
        if(firstp >= 50) {
        $('.forty_section11').css({'color':'blue'});
        }
        else
        {
        $('.forty_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.forty_section9').val());
           var ab = parseInt($('.forty_section10').val());
           var yz = parseInt($('.forty_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.forty_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.forty_section13').val());
        var firstp = firstxy / 480 * 100;
        if(firstp >= 50) {
        $('.forty_section13').css({'color':'blue'});
        }
        else
        {
        $('.forty_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.forty_section4').val());
           var ab = parseInt($('.forty_section8').val());
           var yz = parseInt($('.forty_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.forty_section13').val(xy + yz + ab);
           
        //   other Section
                
             var firstxy = parseInt($('.other_section1').val());
        var firstp = firstxy / 110 * 100;
        if(firstp >= 50) {
        $('.other_section1').css({'color':'blue'});
        }
        else
        {
        $('.other_section1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section2').val());
        var firstp = firstxy / 110 * 100;
        if(firstp >= 50) {
        $('.other_section2').css({'color':'blue'});
        }
        else
        {
        $('.other_section2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section3').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.other_section3').css({'color':'blue'});
        }
        else
        {
        $('.other_section3').css({'color':'Red'});
        }
           var xy = parseInt($('.other_section1').val());
           var ab = parseInt($('.other_section2').val());
           var yz = parseInt($('.other_section3').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.other_section5').val());
        var firstp = firstxy / 110 * 100;
        if(firstp >= 50) {
        $('.other_section5').css({'color':'blue'});
        }
        else
        {
        $('.other_section5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section6').val());
        var firstp = firstxy / 110 * 100;
        if(firstp >= 50) {
        $('.other_section6').css({'color':'blue'});
        }
        else
        {
        $('.other_section6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section7').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.other_section7').css({'color':'blue'});
        }
        else
        {
        $('.other_section7').css({'color':'Red'});
        }
           var xy = parseInt($('.other_section5').val());
           var ab = parseInt($('.other_section6').val());
           var yz = parseInt($('.other_section7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.other_section9').val());
        var firstp = firstxy / 110 * 100;
        if(firstp >= 50) {
        $('.other_section9').css({'color':'blue'});
        }
        else
        {
        $('.other_section9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section10').val());
        var firstp = firstxy / 110 * 100;
        if(firstp >= 50) {
        $('.other_section10').css({'color':'blue'});
        }
        else
        {
        $('.other_section10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section11').val());
        var firstp = firstxy / 120 * 100;
        if(firstp >= 50) {
        $('.other_section11').css({'color':'blue'});
        }
        else
        {
        $('.other_section11').css({'color':'Red'});
        }
           var xy = parseInt($('.other_section9').val());
           var ab = parseInt($('.other_section10').val());
           var yz = parseInt($('.other_section11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.other_section13').val());
        var firstp = firstxy / 1320 * 100;
        if(firstp >= 50) {
        $('.other_section13').css({'color':'blue'});
        }
        else
        {
        $('.other_section13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.other_section4').val());
           var ab = parseInt($('.other_section8').val());
           var yz = parseInt($('.other_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section13').val(xy + yz + ab);
           
           
        //   other second
        
                   
             var firstxy = parseInt($('.other_section_one1').val());
        var firstp = firstxy / 280 * 100;
        if(firstp >= 50) {
        $('.other_section_one1').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one1').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section_one2').val());
        var firstp = firstxy / 280 * 100;
        if(firstp >= 50) {
        $('.other_section_one2').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one2').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section_one3').val());
        var firstp = firstxy / 560 * 100;
        if(firstp >= 50) {
        $('.other_section_one3').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one3').css({'color':'Red'});
        }
        
        var xy = parseInt($('.other_section_one1').val());
        var ab = parseInt($('.other_section_one2').val());
        var yz = parseInt($('.other_section_one3').val());
        if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
        $('.other_section_one4').val(xy + yz + ab);
           
        var firstxy = parseInt($('.other_section_one5').val());
        var firstp = firstxy / 280 * 100;
        if(firstp >= 50) {
        $('.other_section_one5').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one5').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section_one6').val());
        var firstp = firstxy / 280 * 100;
        if(firstp >= 50) {
        $('.other_section_one6').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one6').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section_one7').val());
        var firstp = firstxy / 560 * 100;
        if(firstp >= 50) {
        $('.other_section_one7').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one7').css({'color':'Red'});
        }
           var xy = parseInt($('.other_section_one5').val());
           var ab = parseInt($('.other_section_one6').val());
           var yz = parseInt($('.other_section_one7').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section_one8').val(xy + yz + ab);
           
        var firstxy = parseInt($('.other_section_one9').val());
        var firstp = firstxy / 280 * 100;
        if(firstp >= 50) {
        $('.other_section_one9').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one9').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section_one10').val());
        var firstp = firstxy / 280 * 100;
        if(firstp >= 50) {
        $('.other_section_one10').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one10').css({'color':'Red'});
        }
        
         var firstxy = parseInt($('.other_section_one11').val());
        var firstp = firstxy / 560 * 100;
        if(firstp >= 50) {
        $('.other_section_one11').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one11').css({'color':'Red'});
        }
           var xy = parseInt($('.other_section_one9').val());
           var ab = parseInt($('.other_section_one10').val());
           var yz = parseInt($('.other_section_one11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section_one12').val(xy + yz + ab);
 
  var firstxy = parseInt($('.other_section_one13').val());
        var firstp = firstxy / 3360 * 100;
        if(firstp >= 50) {
        $('.other_section_one13').css({'color':'blue'});
        }
        else
        {
        $('.other_section_one13').css({'color':'Red'});
        }
 
        //horizontal work
         var xy = parseInt($('.other_section_one4').val());
           var ab = parseInt($('.other_section_one8').val());
           var yz = parseInt($('.other_section_one12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.other_section_one13').val(xy + yz + ab);
           
           
        //   vertical calculation
        
        
             var xy = parseInt($('.first_section1').val());
           var yz = parseInt($('.second_section1').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.forty_section1').val(xy + yz );
           
              var xy = parseInt($('.tens_section_one1').val());
           var yz = parseInt($('.third_section1').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one1').val(xy + yz );
           
             var non = parseInt($('.thirty_section_second1').val());
             var xy = parseInt($('.tens_section_second1').val());
           var yz = parseInt($('.tens_section_third1').val());
           var xx = parseInt($('.fourth_section1').val());
           var yy = parseInt($('.fifth_section1').val());
           var zz = parseInt($('.sixth_section1').val());
           var aa = parseInt($('.seventh_section1').val());
           var ab = parseInt($('.tens_section_fourth1').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section1').val(xy + yz + xx + yy + zz + aa +ab);
              var xy = parseInt($('.tens_section_fifth1').val());
           var yz = parseInt($('.tens_section_sixth1').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.eighth_section1').val(xy + yz );
                  var xy = parseInt($('.tens_section_seven1').val());
           var yz = parseInt($('.tens_section_eight1').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.nineth_section1').val(xy + yz );
             var xy = parseInt($('.tens_section_nine1').val());
           var ab = parseInt($('.tens_section_ten1').val());
           var yz = parseInt($('.tens_section_eleven1').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)) { ab = 0; }
           $('.thirty_section_third1').val(xy + yz + ab);
            var xy = parseInt($('.forty_section1').val());
           var yz = parseInt($('.thirty_section_one1').val());
           var xx = parseInt($('.thirty_section_second1').val());
           var yy = parseInt($('.other_section1').val());
           var zz = parseInt($('.eighth_section1').val());
           var aa = parseInt($('.nineth_section1').val());
           var ab = parseInt($('.thirty_section_third1').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section_one1').val(xy + yz + xx + yy + zz + aa +ab);
              
            // second vertical  
                var xy = parseInt($('.first_section2').val());
           var yz = parseInt($('.second_section2').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.forty_section2').val(xy + yz );
              var xy = parseInt($('.tens_section_one2').val());
           var yz = parseInt($('.third_section2').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one2').val(xy + yz );
             var non = parseInt($('.thirty_section_second2').val());
             var xy = parseInt($('.tens_section_second2').val());
           var yz = parseInt($('.tens_section_third2').val());
           var xx = parseInt($('.fourth_section2').val());
           var yy = parseInt($('.fifth_section2').val());
           var zz = parseInt($('.sixth_section2').val());
           var aa = parseInt($('.seventh_section2').val());
           var ab = parseInt($('.tens_section_fourth2').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section2').val(xy + yz + xx + yy + zz + aa +ab);
var xy = parseInt($('.tens_section_fifth2').val());
var yz = parseInt($('.tens_section_sixth2').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.eighth_section2').val(xy + yz );
var xy = parseInt($('.tens_section_seven2').val());
var yz = parseInt($('.tens_section_eight2').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.nineth_section2').val(xy + yz );
             var xy = parseInt($('.tens_section_nine2').val());
           var ab = parseInt($('.tens_section_ten2').val());
           var yz = parseInt($('.tens_section_eleven2').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third2').val(xy + yz + ab);
            var xy = parseInt($('.forty_section2').val());
           var yz = parseInt($('.thirty_section_one2').val());
           var xx = parseInt($('.thirty_section_second2').val());
           var yy = parseInt($('.other_section2').val());
           var zz = parseInt($('.eighth_section2').val());
           var aa = parseInt($('.nineth_section2').val());
           var ab = parseInt($('.thirty_section_third2').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section_one5').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   third
               var xy = parseInt($('.first_section3').val());
           var yz = parseInt($('.second_section3').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.forty_section3').val(xy + yz );
              var xy = parseInt($('.tens_section_one3').val());
           var yz = parseInt($('.third_section3').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one3').val(xy + yz );
var non = parseInt($('.thirty_section_second3').val());
var xy = parseInt($('.tens_section_second3').val());
var yz = parseInt($('.tens_section_third3').val());
var xx = parseInt($('.fourth_section3').val());
var yy = parseInt($('.fifth_section3').val());
var zz = parseInt($('.sixth_section3').val());
var aa = parseInt($('.seventh_section3').val());
var ab = parseInt($('.tens_section_fourth3').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section3').val(xy + yz + xx + yy + zz + aa +ab);
              var xy = parseInt($('.tens_section_fifth3').val());
           var yz = parseInt($('.tens_section_sixth3').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.eighth_section3').val(xy + yz );
                  var xy = parseInt($('.tens_section_seven3').val());
           var yz = parseInt($('.tens_section_eight3').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.nineth_section3').val(xy + yz );
             var xy = parseInt($('.tens_section_nine3').val());
           var ab = parseInt($('.tens_section_ten3').val());
           var yz = parseInt($('.tens_section_eleven3').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third3').val(xy + yz + ab);
var xy = parseInt($('.forty_section3').val());
var yz = parseInt($('.thirty_section_one3').val());
var xx = parseInt($('.thirty_section_second3').val());
var yy = parseInt($('.other_section3').val());
var zz = parseInt($('.eighth_section3').val());
var aa = parseInt($('.nineth_section3').val());
var ab = parseInt($('.thirty_section_third3').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section_one3').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   fourth
            
               var xy = parseInt($('.first_section4').val());
           var yz = parseInt($('.second_section4').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.forty_section4').val(xy + yz );
              var xy = parseInt($('.tens_section_one4').val());
           var yz = parseInt($('.third_section4').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one4').val(xy + yz );
             var non = parseInt($('.thirty_section_second4').val());
             var xy = parseInt($('.tens_section_second4').val());
           var yz = parseInt($('.tens_section_third4').val());
           var xx = parseInt($('.fourth_section4').val());
           var yy = parseInt($('.fifth_section4').val());
           var zz = parseInt($('.sixth_section4').val());
           var aa = parseInt($('.seventh_section4').val());
           var ab = parseInt($('.tens_section_fourth4').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
              $('.other_section4').val(xy + yz + xx + yy + zz + aa +ab);
              var xy = parseInt($('.tens_section_fifth4').val());
           var yz = parseInt($('.tens_section_sixth4').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.eighth_section4').val(xy + yz );
                  var xy = parseInt($('.tens_section_seven4').val());
           var yz = parseInt($('.tens_section_eight4').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.nineth_section4').val(xy + yz );
             var xy = parseInt($('.tens_section_nine4').val());
           var ab = parseInt($('.tens_section_ten4').val());
           var yz = parseInt($('.tens_section_eleven4').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third4').val(xy + yz + ab);
var xy = parseInt($('.forty_section4').val());
var yz = parseInt($('.thirty_section_one4').val());
var xx = parseInt($('.thirty_section_second4').val());
var yy = parseInt($('.other_section4').val());
var zz = parseInt($('.eighth_section4').val());
var aa = parseInt($('.nineth_section4').val());
var ab = parseInt($('.thirty_section_third4').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section_one4').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   fifth
            
               var xy = parseInt($('.first_section5').val());
           var yz = parseInt($('.second_section5').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.forty_section5').val(xy + yz );
              var xy = parseInt($('.tens_section_one5').val());
           var yz = parseInt($('.third_section5').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one5').val(xy + yz );
var non = parseInt($('.thirty_section_second5').val());
var xy = parseInt($('.tens_section_second5').val());
var yz = parseInt($('.tens_section_third5').val());
var xx = parseInt($('.fourth_section5').val());
var yy = parseInt($('.fifth_section5').val());
var zz = parseInt($('.sixth_section5').val());
var aa = parseInt($('.seventh_section5').val());
var ab = parseInt($('.tens_section_fourth5').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section5').val(xy + yz + xx + yy + zz + aa +ab);
              var xy = parseInt($('.tens_section_fifth5').val());
           var yz = parseInt($('.tens_section_sixth5').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.eighth_section5').val(xy + yz );
                  var xy = parseInt($('.tens_section_seven5').val());
           var yz = parseInt($('.tens_section_eight5').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.nineth_section5').val(xy + yz );
             var xy = parseInt($('.tens_section_nine5').val());
           var ab = parseInt($('.tens_section_ten5').val());
           var yz = parseInt($('.tens_section_eleven5').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third5').val(xy + yz + ab);
var xy = parseInt($('.forty_section5').val());
var yz = parseInt($('.thirty_section_one5').val());
var xx = parseInt($('.thirty_section_second5').val());
var yy = parseInt($('.other_section5').val());
var zz = parseInt($('.eighth_section5').val());
var aa = parseInt($('.nineth_section5').val());
var ab = parseInt($('.thirty_section_third5').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section_one5').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   six
            
var xy = parseInt($('.first_section6').val());
var yz = parseInt($('.second_section6').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.forty_section6').val(xy + yz );

var xy = parseInt($('.tens_section_one6').val());
var yz = parseInt($('.third_section6').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.thirty_section_one6').val(xy + yz );

var non = parseInt($('.thirty_section_second6').val());
var xy = parseInt($('.tens_section_second6').val());
var yz = parseInt($('.tens_section_third6').val());
var xx = parseInt($('.fourth_section6').val());
var yy = parseInt($('.fifth_section6').val());
var zz = parseInt($('.sixth_section6').val());
var aa = parseInt($('.seventh_section6').val());
var ab = parseInt($('.tens_section_fourth6').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section6').val(xy + yz + xx + yy + zz + aa +ab);
var xy = parseInt($('.tens_section_fifth6').val());
var yz = parseInt($('.tens_section_sixth6').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.eighth_section6').val(xy + yz );
  var xy = parseInt($('.tens_section_seven6').val());
var yz = parseInt($('.tens_section_eight6').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.nineth_section6').val(xy + yz );
var xy = parseInt($('.tens_section_nine6').val());
var ab = parseInt($('.tens_section_ten6').val());
var yz = parseInt($('.tens_section_eleven6').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third6').val(xy + yz + ab);
            var xy = parseInt($('.forty_section6').val());
           var yz = parseInt($('.thirty_section_one6').val());
           var xx = parseInt($('.thirty_section_second6').val());
           var yy = parseInt($('.other_section6').val());
           var zz = parseInt($('.eighth_section6').val());
           var aa = parseInt($('.nineth_section6').val());
           var ab = parseInt($('.thirty_section_third6').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
              $('.other_section_one6').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   seven
            
var xy = parseInt($('.first_section7').val());
var yz = parseInt($('.second_section7').val());
  if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.forty_section7').val(xy + yz );
var xy = parseInt($('.tens_section_one7').val());
var yz = parseInt($('.third_section7').val());
  if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.thirty_section_one7').val(xy + yz );
var non = parseInt($('.thirty_section_second7').val());
var xy = parseInt($('.tens_section_second7').val());
var yz = parseInt($('.tens_section_third7').val());
var xx = parseInt($('.fourth_section7').val());
var yy = parseInt($('.fifth_section7').val());
var zz = parseInt($('.sixth_section7').val());
var aa = parseInt($('.seventh_section7').val());
var ab = parseInt($('.tens_section_fourth7').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section7').val(xy + yz + xx + yy + zz + aa +ab);
var xy = parseInt($('.tens_section_fifth7').val());
var yz = parseInt($('.tens_section_sixth7').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.eighth_section7').val(xy + yz );
var xy = parseInt($('.tens_section_seven7').val());
var yz = parseInt($('.tens_section_eight7').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.nineth_section7').val(xy + yz );
             var xy = parseInt($('.tens_section_nine7').val());
           var ab = parseInt($('.tens_section_ten7').val());
           var yz = parseInt($('.tens_section_eleven7').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third7').val(xy + yz + ab);
var xy = parseInt($('.forty_section7').val());
var yz = parseInt($('.thirty_section_one7').val());
var xx = parseInt($('.thirty_section_second7').val());
var yy = parseInt($('.other_section7').val());
var zz = parseInt($('.eighth_section7').val());
var aa = parseInt($('.nineth_section7').val());
var ab = parseInt($('.thirty_section_third7').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section_one7').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   eight
               var xy = parseInt($('.first_section8').val());
           var yz = parseInt($('.second_section8').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.forty_section8').val(xy + yz );
              var xy = parseInt($('.tens_section_one8').val());
           var yz = parseInt($('.third_section8').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one8').val(xy + yz );
             var non = parseInt($('.thirty_section_second8').val());
             var xy = parseInt($('.tens_section_second8').val());
           var yz = parseInt($('.tens_section_third8').val());
           var xx = parseInt($('.fourth_section8').val());
           var yy = parseInt($('.fifth_section8').val());
           var zz = parseInt($('.sixth_section8').val());
           var aa = parseInt($('.seventh_section8').val());
           var ab = parseInt($('.tens_section_fourth8').val());
             if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section8').val(xy + yz + xx + yy + zz + aa +ab);
              var xy = parseInt($('.tens_section_fifth8').val());
           var yz = parseInt($('.tens_section_sixth8').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.eighth_section8').val(xy + yz );
                  var xy = parseInt($('.tens_section_seven8').val());
           var yz = parseInt($('.tens_section_eight8').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.nineth_section8').val(xy + yz );
             var xy = parseInt($('.tens_section_nine8').val());
           var ab = parseInt($('.tens_section_ten8').val());
           var yz = parseInt($('.tens_section_eleven8').val());
            if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third8').val(xy + yz + ab);
            var xy = parseInt($('.forty_section8').val());
           var yz = parseInt($('.thirty_section_one8').val());
           var xx = parseInt($('.thirty_section_second8').val());
           var yy = parseInt($('.other_section8').val());
           var zz = parseInt($('.eighth_section8').val());
           var aa = parseInt($('.nineth_section8').val());
           var ab = parseInt($('.thirty_section_third8').val());
             if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section_one8').val(xy + yz + xx + yy + zz + aa +ab);
           
        //   nine
           var xy = parseInt($('.first_section9').val());
           var yz = parseInt($('.second_section9').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.forty_section9').val(xy + yz );
              var xy = parseInt($('.tens_section_one9').val());
           var yz = parseInt($('.third_section9').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one9').val(xy + yz );
             var non = parseInt($('.thirty_section_second9').val());
             var xy = parseInt($('.tens_section_second9').val());
           var yz = parseInt($('.tens_section_third9').val());
           var xx = parseInt($('.fourth_section9').val());
           var yy = parseInt($('.fifth_section9').val());
           var zz = parseInt($('.sixth_section9').val());
           var aa = parseInt($('.seventh_section9').val());
           var ab = parseInt($('.tens_section_fourth9').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section9').val(xy + yz + xx + yy + zz + aa +ab);
              var xy = parseInt($('.tens_section_fifth9').val());
           var yz = parseInt($('.tens_section_sixth9').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.eighth_section9').val(xy + yz );
                  var xy = parseInt($('.tens_section_seven9').val());
           var yz = parseInt($('.eight_section9').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
           $('.nineth_section9').val(xy + yz );
             var xy = parseInt($('.tens_section_nine9').val());
           var ab = parseInt($('.tens_section_ten9').val());
           var yz = parseInt($('.tens_section_eleven9').val());
           if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third9').val(xy + yz + ab);
            var xy = parseInt($('.forty_section9').val());
           var yz = parseInt($('.thirty_section_one9').val());
           var xx = parseInt($('.thirty_section_second9').val());
           var yy = parseInt($('.other_section9').val());
           var zz = parseInt($('.eighth_section9').val());
           var aa = parseInt($('.nineth_section9').val());
           var ab = parseInt($('.thirty_section_third9').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)){ ab = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
              $('.other_section_one9').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   ten
            
var xy = parseInt($('.first_section10').val());
var yz = parseInt($('.second_section10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.forty_section10').val(xy + yz );
var xy = parseInt($('.tens_section_one10').val());
var yz = parseInt($('.third_section10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.thirty_section_one10').val(xy + yz );
var non = parseInt($('.thirty_section_second10').val());
var xy = parseInt($('.tens_section_second10').val());
var yz = parseInt($('.tens_section_third10').val());
var xx = parseInt($('.fourth_section10').val());
var yy = parseInt($('.fifth_section10').val());
var zz = parseInt($('.sixth_section10').val());
var aa = parseInt($('.seventh_section10').val());
var ab = parseInt($('.tens_section_fourth10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
if(isNaN(non)){ non = 0; }
$('.other_section10').val(xy + yz + xx + yy + zz + aa +ab);
var xy = parseInt($('.tens_section_fifth10').val());
var yz = parseInt($('.tens_section_sixth10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.eighth_section10').val(xy + yz );
var xy = parseInt($('.tens_section_seven10').val());
var yz = parseInt($('.tens_section_eight10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.nineth_section10').val(xy + yz );
var xy = parseInt($('.tens_section_nine10').val());
var ab = parseInt($('.tens_section_ten10').val());
var yz = parseInt($('.tens_section_eleven10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(ab)){ ab = 0; }
$('.thirty_section_third10').val(xy + yz + ab);
var xy = parseInt($('.forty_section10').val());
var yz = parseInt($('.thirty_section_one10').val());
var xx = parseInt($('.thirty_section_second10').val());
var yy = parseInt($('.other_section10').val());
var zz = parseInt($('.eighth_section10').val());
var aa = parseInt($('.nineth_section10').val());
var ab = parseInt($('.thirty_section_third10').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
if(isNaN(non)){ non = 0; }
$('.other_section_one10').val(xy + yz + xx + yy + zz + aa +ab);
//   eleven
var xy = parseInt($('.first_section11').val());
var yz = parseInt($('.second_section11').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.forty_section11').val(xy + yz );
var xy = parseInt($('.tens_section_one11').val());
var yz = parseInt($('.third_section11').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.thirty_section_one11').val(xy + yz );
var non = parseInt($('.thirty_section_second11').val());
var xy = parseInt($('.tens_section_second11').val());
var yz = parseInt($('.tens_section_third11').val());
var xx = parseInt($('.fourth_section11').val());
var yy = parseInt($('.fifth_section11').val());
var zz = parseInt($('.sixth_section11').val());
var aa = parseInt($('.seventh_section11').val());
var ab = parseInt($('.tens_section_fourth11').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
if(isNaN(non)){ non = 0; }
$('.other_section11').val(xy + yz + xx + yy + zz + aa +ab);
              
var xy = parseInt($('.tens_section_fifth11').val());
var yz = parseInt($('.tens_section_sixth11').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.eighth_section11').val(xy + yz );

var xy = parseInt($('.tens_section_seven11').val());
var yz = parseInt($('.tens_section_eight11').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
$('.nineth_section11').val(xy + yz );
           
             var xy = parseInt($('.tens_section_nine11').val());
           var ab = parseInt($('.tens_section_ten11').val());
           var yz = parseInt($('.tens_section_eleven11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third11').val(xy + yz + ab);
           
            var xy = parseInt($('.forty_section11').val());
           var yz = parseInt($('.thirty_section_one11').val());
           var xx = parseInt($('.thirty_section_second11').val());
           var yy = parseInt($('.other_section11').val());
           var zz = parseInt($('.eighth_section11').val());
           var aa = parseInt($('.nineth_section11').val());
           var ab = parseInt($('.thirty_section_third11').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; } 
        if(isNaN(ab)){ ab = 0; }
              $('.other_section_one11').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   twelev
               var xy = parseInt($('.first_section12').val());
           var yz = parseInt($('.second_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.forty_section12').val(xy + yz );
              var xy = parseInt($('.tens_section_one12').val());
           var yz = parseInt($('.third_section12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one12').val(xy + yz );
           
             var non = parseInt($('.thirty_section_second12').val());
             var xy = parseInt($('.tens_section_second12').val());
           var yz = parseInt($('.tens_section_third12').val());
           var xx = parseInt($('.fourth_section12').val());
           var yy = parseInt($('.fifth_section12').val());
           var zz = parseInt($('.sixth_section12').val());
           var aa = parseInt($('.seventh_section12').val());
           var ab = parseInt($('.tens_section_fourth12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
        if(isNaN(non)){ non = 0; }
              $('.other_section12').val(xy + yz + xx + yy + zz + aa +ab);
              
              var xy = parseInt($('.tens_section_fifth12').val());
           var yz = parseInt($('.tens_section_sixth12').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.eighth_section12').val(xy + yz );
           
                  var xy = parseInt($('.tens_section_seven12').val());
           var yz = parseInt($('.tens_section_eight12').val());
            if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.nineth_section12').val(xy + yz );
           
             var xy = parseInt($('.tens_section_nine12').val());
           var ab = parseInt($('.tens_section_ten12').val());
           var yz = parseInt($('.tens_section_eleven12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third12').val(xy + yz + ab);
           
            var xy = parseInt($('.forty_section12').val());
           var yz = parseInt($('.thirty_section_one12').val());
           var xx = parseInt($('.thirty_section_second12').val());
           var yy = parseInt($('.other_section12').val());
           var zz = parseInt($('.eighth_section12').val());
           var aa = parseInt($('.nineth_section12').val());
           var ab = parseInt($('.thirty_section_third12').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
              $('.other_section_one12').val(xy + yz + xx + yy + zz + aa +ab);
              
            //   thirteen 
            
               var xy = parseInt($('.first_section13').val());
           var yz = parseInt($('.second_section13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.forty_section13').val(xy + yz );
           
              var xy = parseInt($('.tens_section_one13').val());
           var yz = parseInt($('.third_section13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.thirty_section_one13').val(xy + yz );
           
             var non = parseInt($('.thirty_section_second13').val());
             var xy = parseInt($('.tens_section_second13').val());
           var yz = parseInt($('.tens_section_third13').val());
           var xx = parseInt($('.fourth_section13').val());
           var yy = parseInt($('.fifth_section13').val());
           var zz = parseInt($('.sixth_section13').val());
           var aa = parseInt($('.seventh_section13').val());
           var ab = parseInt($('.tens_section_fourth13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
        if(isNaN(non)){ non = 0; }
              $('.other_section13').val(xy + yz + xx + yy + zz + aa +ab);
              
              var xy = parseInt($('.tens_section_fifth13').val());
           var yz = parseInt($('.tens_section_sixth13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.eighth_section13').val(xy + yz );
           
                  var xy = parseInt($('.tens_section_seven13').val());
           var yz = parseInt($('.tens_section_eight13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
           $('.nineth_section13').val(xy + yz );
           
             var xy = parseInt($('.tens_section_nine13').val());
           var ab = parseInt($('.tens_section_ten13').val());
           var yz = parseInt($('.tens_section_eleven13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
         if(isNaN(ab)){ ab = 0; }
           $('.thirty_section_third13').val(xy + yz + ab);
           
            var xy = parseInt($('.forty_section13').val());
           var yz = parseInt($('.thirty_section_one13').val());
           var xx = parseInt($('.thirty_section_second13').val());
           var yy = parseInt($('.other_section13').val());
           var zz = parseInt($('.eighth_section13').val());
           var aa = parseInt($('.nineth_section13').val());
           var ab = parseInt($('.thirty_section_third13').val());
           if(isNaN(yz)){ yz = 0; }
        if(isNaN(xy))  { xy = 0; }
        if(isNaN(xx)){ xx = 0; }
        if(isNaN(yy))  { yy = 0; }
        if(isNaN(zz)){ zz = 0; }
        if(isNaN(aa))  { aa = 0; }
        if(isNaN(ab)){ ab = 0; }
        $('.other_section_one13').val(xy + yz + xx + yy + zz + aa +ab);
        
        var xy = parseInt($('.forty_section2').val());
var yz = parseInt($('.thirty_section_one2').val());
var xx = parseInt($('.thirty_section_second3').val());
var yy = parseInt($('.other_section2').val());
var zz = parseInt($('.eighth_section2').val());
var aa = parseInt($('.nineth_section2').val());
var ab = parseInt($('.thirty_section_third2').val());
if(isNaN(yz)){ yz = 0; }
if(isNaN(xy))  { xy = 0; }
if(isNaN(xx)){ xx = 0; }
if(isNaN(yy))  { yy = 0; }
if(isNaN(zz)){ zz = 0; }
if(isNaN(aa))  { aa = 0; }
if(isNaN(ab)){ ab = 0; }
$('.other_section_one2').val(xy + yz + xx + yy + zz + aa +ab);
        
        var firstxy = parseInt($('.other_section_one1').val());
        var firstp = firstxy / 280 * 100;
        $('.xy1').val(firstp);
        
        var firstxy = parseInt($('.other_section_one2').val());
        var firstp = firstxy / 280 * 100;
        $('.xy2').val(firstp);
        
        var firstxy = parseInt($('.other_section_one3').val());
        var firstp = firstxy / 560 * 100;
        $('.xy3').val(firstp);
        
        var firstxy = parseInt($('.other_section_one4').val());
        var firstp = firstxy / 1120 * 100;
        $('.xy4').val(firstp);
        
        var firstxy = parseInt($('.other_section_one5').val());
        var firstp = firstxy / 280 * 100;
        $('.xy5').val(firstp);
        
        var firstxy = parseInt($('.other_section_one6').val());
        var firstp = firstxy / 280 * 100;
        $('.xy6').val(firstp);
        
        var firstxy = parseInt($('.other_section_one7').val());
        var firstp = firstxy / 560 * 100;
        $('.xy7').val(firstp);
        
        var firstxy = parseInt($('.other_section_one8').val());
        var firstp = firstxy / 1120 * 100; 
        $('.xy8').val(firstp);
        
        var firstxy = parseInt($('.other_section_one9').val());
        var firstp = firstxy / 280 * 100;
        $('.xy9').val(firstp);
        
        var firstxy = parseInt($('.other_section_one10').val());
        var firstp = firstxy / 280 * 100;
        $('.xy10').val(firstp);
        
        var firstxy = parseInt($('.other_section_one11').val());
        var firstp = firstxy / 560 * 100;
        $('.xy11').val(firstp);
        
        var firstxy = parseInt($('.other_section_one12').val());
        var firstp = firstxy / 1120 * 100; 
        $('.xy12').val(firstp);
        
        var firstxy = parseInt($('.other_section_one13').val());
        var firstp = firstxy / 3360 * 100;
        $('.xy13').val(firstp);
      

        for(var i = 1; i < 28; i++){
            var xy = parseInt($('.mymarks'+i).val());
            var yz = parseInt($('.mymarks2'+i).val());
            var ab = parseInt($('.mymarks3'+i).val());
            if(isNaN(yz)){ yz = 0; }
            if(isNaN(xy))  { xy = 0; }
            if(isNaN(ab)){ ab = 0; }
            var abc =  xy + yz + ab;
            $('.mymaxget'+i).val(abc);
        }

       }, 1000);
</script>