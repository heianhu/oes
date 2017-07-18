//***********************************************//
//* Online Examination System                   *//
//*---------------------------------------------*//
//* License: GNU General Public License V.3     *//
//* Author: Manjunath Baddi                     *//
//* Title: Validation                           *//
//***********************************************//
                function isalphanum(ele)
                {
                    var r=/\W$/i;
                    if(r.test(ele.value))
                     {
                         alert("此字段仅允许字母数字字符。");
                         ele.value="";
                         ele.focus();
                     }
                }
                function isalpha(ele)
                {
                    var r=/[^a-zA-Z]+/i;
                    if(r.test(ele.value))
                     {
                         alert("此字段只允许字母。");
                         ele.value="";
                         ele.focus();
                     }
                }
                function isnum(ele)
                {
                    var r=/\D$/i;
                    if(r.test(ele.value))
                     {
                         alert("此字段只允许数字。");
                         ele.value="";
                         ele.focus();
                     }
                }

                function validateform(mmyform)
                {
                    var em=/[a-zA-Z0-9]+@[a-zA-Z0-9]+.[a-zA-Z]+/;
                    myform=document.forms[mmyform];
                    if(myform.cname.value=="" || myform.password.value=="" || myform.repass.value=="" || myform.email.value=="" || myform.contactno.value=="" || myform.address.value=="" || myform.city.value=="" || myform.pin.value=="")
                     {
                         alert("一些字段为空。");
                         return false;
                         //  myform.onsubmit=false;
                     }
                     else if(myform.password.value!=myform.repass.value)
                         {
                             alert("密码不匹配！");
                            // myform.onsubmit=false;
                            return false;
                         }
                         else if(!em.test(myform.email.value))
                             {
                                 alert("请输入正确的电子邮件！");
                               //  myform.onsubmit=false;
                                 return false;
                             }


                }

                function validatesubform(mmyform)
                {

                    myform=document.forms[mmyform];
                    if(myform.subname.value=="" || myform.subdesc.value=="")
                     {
                         alert("一些字段为空。");
                         myform.onSubmit=false;
                     }
                     
                }
        function validatetestform(mmyform)
                {

                   /* myform=document.forms[mmyform];
                    if(myform.subname.value=="" || myform.subdesc.value=="")
                     {
                         alert("Some of the fields are Empty.");
                         myform.onSubmit=false;
                     }
*/
                }
                function showerror(ele)
                {
					alert(ele);
                }
  /*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


