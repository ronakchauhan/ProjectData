<?php
/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket Private Sales and Flash Sales v4.x.x
 * @copyright   Copyright (c) 2016 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

namespace Plumrocket\PrivateSale\Setup;

/**
 * @codeCoverageIgnore
 */
class EmailTemplateData
{

    public function getDefaultEmailTemplate()
    {
        return <<<ETD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{{title}}</title>
<style type="text/css">
            /* Client-specific Styles */
            #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
            body{width:100% !important;}
            .ReadMsgBody{width:100%;}
            .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
            body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

            /* Reset Styles */
            body{margin:0; padding:0;}
            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none; display: block;}
            table td{border-collapse:collapse;}
            a, a:hover {text-decoration:none;}
            #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}

            /* Template Styles */

            /* /\/\/\/\/\/\/\/\/\/\ STANDARD STYLING: COMMON PAGE ELEMENTS /\/\/\/\/\/\/\/\/\/\ */

            /**
            * @tab Page
            * @section background color
            * @tip Set the background color for your email. You may want to choose one that matches your company's branding.
            * @theme page
            */
            body, #backgroundTable{
                background-color:#fff;
                font-family:Arial, Helvetica, sans-serif;
                font-size:14px;
                color:#464646;
            }

            #mainholder {
                background-color:#fff;
            }

            /* /\/\/\/\/\/\/\/\/\/\ PLUMROCKET STYLING /\/\/\/\/\/\/\/\/\/\ */

            #header_text {font-size:11px; text-align:center;}
            #header_text a:link, #header_text a:visited, /* Yahoo! Mail Override */ #header_text a .yshortcuts /* Yahoo! Mail Override */{
                /*@editable*/color:#e50213;
                text-decoration:underline;
            }

            .title-newsletter {
                text-align: center;
                /*@editable*/font-size: 21px;
            }

            .title-event,
            .title-event a,
            .title-event a:link, .title-event a:visited, /* Yahoo! Mail Override */
            .title-event a .yshortcuts /* Yahoo! Mail Override */{
                font-weight: 700;
                /*@editable*/color:#fff;
                /*@editable*/background: #e10415;
            }
            .title-event img {display: inline; vertical-align: middle;}
            .first-footer td {text-align: center;}

            .first-footer a,
            .first-footer a:link, .first-footer a:visited, /* Yahoo! Mail Override */
            .first-footer a .yshortcuts /* Yahoo! Mail Override */{
                /*@editable*/color:#e50213;
            }


            #footer_block {font-size: 12px; text-align: center; color:#262626;}
            #footer_block a:link, #footer_block a:visited, /* Yahoo! Mail Override */
            #footer_block a .yshortcuts /* Yahoo! Mail Override */{
                    text-decoration:none;
                    color:#e50213 !important;
                    text-decoration:underline;
            }

            #copyrights-block {font-size: 10px; text-align: center;}




            @media only screen and (max-device-width: 480px){
                #header_text, #footer_block, #header_text a, #footer_block a, #footer_block p, .med { font-size:17px!important;}
             }


</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center>
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable">
                <tr>
                    <!-- main holder -->
                    <td align="center" valign="top" style="padding:10px 0 25px 0;">
                        <table border="0" cellpadding="0" cellspacing="15" width="640">
                            <tr>
                                <td id="header_text">
                                    <div style="text-align:center;" mc:edit="std_preheader_links" class="regularlink">Use this area to offer a short teaser of your email's content. Text here will show in the preview area of some email clients.<br><br>
                                        Is this email not displaying correctly? <a href="*|ARCHIVE|*" target="_blank">View it in your browser</a>.
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table border="0" cellpadding="0" cellspacing="0" width="640" id="mainholder">
                            <tr>
                                <td>
                                    <table border="0" cellpadding="0" cellspacing="15" width="640">
                                        <tr>
                                            <td class="logo" style="text-align:left;" align="left" valign="top" mc:edit="left-logo-block">
                                                <a href="{{store url=/'}}">
                                                    <img src="{{view url=Plumrocket_PrivateSale::images/emailtemplate/logo-newsletter.gif}}" alt="event-name-here">
                                                </a>
                                            </td>
                                            <td style="text-align:right;" align="right" valign="top" mc:edit="right-block-edit">
                                                <a href="{{store url='invitations/promo/'}}">Invite Friends</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" cellpadding="0" cellspacing="15" width="640">
                                        <tr>
                                            <td style="padding:40px 0 5px 0; text-align: center;" align="center">
                                                <div class="title-newsletter" mc:edit="newsletter-head">
                                                    {{title}}
                                                </div>
                                                <div style="text-align: center;" mc:edit="newsletter-dates">
                                                    {{period}}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                                    {{events_list}}
                            <tr>
                                <td style="padding:10px 5px 10px 5px;" class="first-footer">
                                    <table border="0" cellpadding="10" cellspacing="0" width="630">
                                        <tr>
                                            <td width="33%" valign="top" mc:edit="frist-footer-block1">
                                                <strong>header</strong>
                                                <br>
                                                some text go here my be some <a href="{{store url='invitations/promo/'}}">links</a> also.
                                            </td>
                                            <td width="33%" valign="top" mc:edit="frist-footer-block2">
                                                <strong>header</strong>
                                                <br>
                                                some text go here my be some <a href="{{store url='invitations/promo/'}}">links</a> also.
                                            </td>
                                            <td width="33%" valign="top" mc:edit="frist-footer-block3">
                                                <strong>header</strong>
                                                <br>
                                                some text go here my be some <a href="{{store url='invitations/promo/'}}">links</a> also.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr> <!-- // first footer -->
                            <tr>
                                <td style="padding:5px 5px 5px 5px;" id="footer_block" mc:edit="footer-links">
                                     <a href="*|UPDATE_PROFILE|*">update subscription preferences</a> | <a href="*|UNSUB|*">unsubscribe from this list</a> 
                                </td>
                            </tr>
                             <tr>
                                <td style="padding:0px 5px 0px 5px;" id="copyrights-block" mc:edit="copyrights">
<em>Copyright © *|CURRENT_YEAR|* *|LIST:COMPANY|*, All rights reserved.</em>
                                </td>
                            </tr>
                        </table>
                    </td><!--// main holder -->
                </tr>
            </table>
        </center>
    </body>
</html>
ETD;
    }

    public function getListTemplateOne()
    {
        return <<<LTDO
        <tr>
            <td style="padding:0 15px 15px 15px;">
                <table border="0" cellpadding="0" cellspacing="0" width="608" style="border:1px solid #e10415;">
                    <tr>
                        <td colspan="2">
                            <a href="{{event.url}}"><img src="{{event.image}}" alt="{{event.page_title}}" style="width:608px;"></a>
                        </td>
                    </tr>
                    <tr>
                        <td alight="left" class="title-event" height="36" valign="middle" style="padding:0 0 0 5px;" mc:edit="event-title-{{n}}">
                            {{event.short_page_title}}
                        </td>
                        <td align="right" class="title-event" height="36" valign="middle" style="padding:0 5px 0;">
                            <a href="{{event.url}}"><span>Shop</span> <img src="{{view url=Plumrocket_PrivateSale::images/emailtemplate/arrow.png}}" alt="arrow"></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
LTDO;
    }

    public function getListTemplateTwo()
    {
        return <<<LTDT
        <tr>
            <td style="padding:0 15px 15px 15px;">
                <table  border="0" cellpadding="0" cellspacing="0" width="610">
                    <tr>
                        <td>
                            <!-- event 1 -->
                            <table table border="0" cellpadding="0" cellspacing="0" width="297" style="border:1px solid #e10415;">
                                <tr>
                                    <td colspan="2">
                                        <a href="{{event.url}}"><img src="{{event.image}}" alt="{{event.page_title}}" style="width:297px;"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td alight="left" class="title-event" height="36" valign="middle" style="padding:0 0 0 5px;" mc:edit="event-title1row-{{n}}">
                                        {{event.short_page_title}}
                                    </td>
                                </tr>
                            </table>
                            <!-- event 1 -->
                        </td>

                        <td width="16" style="width:16px;"> </td>

                        <td>
                            <!-- event 2 -->
                             <table  border="0" cellpadding="0" cellspacing="0" width="297" style="border:1px solid #e10415;">
                                <tr>
                                    <td colspan="2">
                                        <a href="{{event2.url}}"><img src="{{event2.image}}" alt="{{event2.page_title}}"  style="width:297px;"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td alight="left" class="title-event" height="36" valign="middle" style="padding:0 0 0 5px;" mc:edit="event-title2row-{{n}}">
                                        {{event2.short_page_title}}
                                    </td>
                                </tr>
                            </table>
                            <!-- event 2 -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
LTDT;
    }

    public function getListTemplateThree()
    {
        return <<<LTDTH
        <tr>
            <td style="padding:0 15px 15px 15px;">
                <table  border="0" cellpadding="0" cellspacing="0" width="610">
                    <tr>
                        <td width="198" style="width:198px;">
                            <!-- event 1 -->
                            <table table border="0" cellpadding="0" cellspacing="0" width="198" style="border:1px solid #e10415;">
                                <tr>
                                    <td colspan="2">
                                        <a href="{{event.url}}"><img src="{{event.image}}" alt="{{event.page_title}}" style="width:198px;"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td alight="left" class="title-event" height="36" valign="middle" style="padding:0 0 0 5px;" mc:edit="event-title1row-{{n}}">
                                        {{event.short_page_title}}
                                    </td>
                                </tr>
                            </table>
                            <!-- event 1 -->
                        </td>

                        <td width="6" style="width:6px;"> </td>

                        <td width="198" style="width:198px;">
                            <!-- event 2 -->
                             <table  border="0" cellpadding="0" cellspacing="0" width="198" style="border:1px solid #e10415;">
                                <tr>
                                    <td colspan="2">
                                        <a href="{{event2.url}}"><img src="{{event2.image}}" alt="{{event2.page_title}}"  style="width:198px;"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td alight="left" class="title-event" height="36" valign="middle" style="padding:0 0 0 5px;" mc:edit="event-title2row-{{n}}">
                                        {{event2.short_page_title}}
                                    </td>
                                </tr>
                            </table>
                            <!-- event 2 -->
                        </td>

                        <td width="6" style="width:6px;"> </td>

                        <td width="198" style="width:198px;">
                            <!-- event 3 -->
                             <table  border="0" cellpadding="0" cellspacing="0" width="198" style="border:1px solid #e10415;">
                                <tr>
                                    <td colspan="2">
                                        <a href="{{event3.url}}"><img src="{{event3.image}}" alt="{{event3.page_title}}"  style="width:198px;"></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td alight="left" class="title-event" height="36" valign="middle" style="padding:0 0 0 5px;" mc:edit="event-title3row-{{n}}">
                                        {{event3.short_page_title}}
                                    </td>
                                </tr>
                            </table>
                            <!-- event 3 -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
LTDTH;
    }

}