<?php $contact_types = ['Email', 'Mobile Phone', 'Business Phone']; ?>
<div class="mj-column-per-100 outlook-group-fix" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;" align="left" width="100%">
    <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
        <tbody>
            <tr>
                <td style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fefefe; border: 1px solid #DFDFDF; vertical-align: top; padding: 20px 0;" bgcolor="#fefefe">
                    <table id="preview_info" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Full Name:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->name }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Company:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->company }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Company Email</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->company_email }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Business Phone Number:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->business_phone }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Ext:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->ext }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Mobile Number:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->mobile_phone }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Best time to reach you:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->meet_time }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Contact Type:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $contact_types[$data->contact_type - 1] }}</span>
                                </div>
                            </td>
                            </tr><tr>
                            <td align="left" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-size: 0px; padding: 10px 25px; word-break: break-word;">
                                <div style="font-family:Open sans, arial, sans-serif;font-size:16px;line-height:25px;text-align:left;color:#363A41;" align="left">
                                    <span class="label" style="font-weight: 700;">Message:</span>
                                    <span style="color:#25B9D7;font-weight:600; text-decoration: underline;">{{ $data->message }}</span>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>