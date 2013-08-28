# List frontend user

Plugin for listing users from table fe_user in frontend.

Contains two frontend plugins.

1. List users from selected groups
2. List currently logged user

For both plugins must be either included static resource in template or
you can define your own typoscript for `plugin.tx_listfeusers_pi2.user`.
This typoscript object `COA` is required.

To this object will be passed in row from table fe_user.

## Database modification

Plugin add a field `fe_pid` to table `fe_groups`. This field is used for association between
group and page. This field is editable in administration of the group in backend area. Despite
this is not used by plugin itself by default.

To get link to the proper page with listed user you can use this code snippet

    10 = COA
          10 {

            10 = TEXT
            10.field = name
            10.if.isTrue.field = name
            10.stdWrap{
                typolink.parameter.stdWrap {
                    dataWrap = db:fe_groups:{field:usergroup}:fe_pid
                    wrap3 = {|}#user-{field:uid}
                    insertData = 1
            }
         }
      }


## Static files

Here are the stacic files, that are provided with the plugins.

### List logged user

    plugin.tx_listfeusers_pi2{
        user = COA
        user {
            10 = COA
            10 {
            stdWrap.dataWrap = <div id="user-{field:uid}" class="user vcard">|</div>
            #name
            10 = COA
            10 {

                30 = TEXT
                30.field = name
                wrap = <h1 class="name fn">|</h1>
            }


            15 = COA
            15 {
                if.isTrue.field = image
                1 = IMAGE
                1 {
                    wrap = <div class="photo">|</div>
                    file = image
                    file.import =  uploads/tx_srfeuserregister/
                    file.import.field = image
                    file.width = 180

                    required = 1
                }
            }


            #contact info
            20 = COA

            20 {
                stdWrap.wrap = <table class="contact">|</table>
                stdWrap.required = 1

                10 = TEXT
                10.field = firma
                10.wrap = <tr><td>Firma</td><td class="value">|</td></div>

                9 = TEXT
                9.field = date_of_birth
                9.wrap = <tr><td>Geburtsdatum</td><td class="value">|</td></div>
                9.if.isTrue.field = date_of_birth

                10 = TEXT
                10.field = telephone
                10.wrap = <tr><td>Telefon</td><td class="value">|</td></div>
                #10.if.isTrue.field = telephone

                20 = TEXT
                20.field = email
                20.wrap = <tr><td>E-Mail</td><td class="value">|</td></tr>
                #20.if.isTrue.field = email

                30 = TEXT
                30.field = fax
                30.wrap = <tr><td>fax:</td><td class="value">|</td></tr>
                30.if.isTrue.field = fax

                40 = TEXT
                40.field = www
                40.wrap = <tr><td>Homepage</td><td class="value">|</td></tr>
                40.if.isTrue.field = www
                40.typolink.parameter.field = www
                40.typolink.extTarget = _blank
            }

            #address
            29 = TEXT
            29.value = <h2>Adresse</h2>
            30 = COA
            30 {
                wrap = <table class="address">|</table>

                10 = TEXT
                10.field = address
                10.wrap = <tr><td>Adresse</td><td class="value">|<//tr>

                19  = TEXT
                19.field = zip
                19.wrap = <tr><td>PLZ</td><td class="value">|</tr>
                #19.if.isTrue.field = zip

                20 = TEXT
                20.field = city
                20.wrap = <tr><td>Wohnort</td><td class="value">|</tr>
                #20.if.isTrue.field = city
            }
        }
    }

    _CSS_DEFAULT_STYLE (
                .tx-listfeusers-pi2 .user .photo {
                    float:right;
                    margin-left: 10px;
                }

                .content .tx-listfeusers-pi2 table{
                    margin: 10px 0px;
                }

                .content .tx-listfeusers-pi2 h1{
                    margin: 0px 10px 10px 10px;
                    padding: 0;
                }

                .content .tx-listfeusers-pi2 h2 {
                    margin: 5px 10px;
                    padding: 0;
                }

                .tx-listfeusers-pi2 table tr td{
                    text-align: right;
                    width: 25%;
                }

                .tx-listfeusers-pi2 table tr td.value {
                    font-weight: bold;
                    text-align: left;
                    width: 75%;
                }
            )
    }

### List frontend user

    plugin.tx_listfeusers_pi1{
        user = COA
        user {
            10 = COA
            10 {
            stdWrap.dataWrap = <div id="user-{field:uid}" class="user vcard">|</div>
            #name
            10 = COA
            10 {
                30 = TEXT
                30.field = name
                wrap = <h1 class="name fn">|</h1>
            }

            11 = TEXT
            11 {
                data = page : title
                wrap = <div class="role">|</div>
            }

            15 = COA
            15 {
                if.isTrue.field = image
                1 = IMAGE
                1 {
                    wrap = <div class="photo">|</div>
                    file = image
                    file.import =  uploads/tx_srfeuserregister/
                    file.import.field = image
                    file.width = 180
                    required = 1
                }
            }


            #contact info
            20 = COA

            20 {
                stdWrap.wrap = <div class="contact">|</div>
                stdWrap.required = 1

                10 = TEXT
                10.field = telephone
                10.wrap = <div class="tel">tel.: |</div>
                10.if.isTrue.field = telephone

                20 = TEXT
                20.field = email
                20.wrap = <div class="email">|</div>
                20.if.isTrue.field = email

                30 = TEXT
                30.field = fax
                30.wrap = <div class="fax">fax: |</div>
                30.if.isTrue.field = fax

                40 = TEXT
                40.field = www
                40.wrap = <div class="url">|</div>
                40.if.isTrue.field = www
                40.typolink.parameter.field = www
                40.typolink.extTarget = _blank
            }

            #address
            30 = COA
            30 {
                wrap = <div class="adr">|</div>
                10 = TEXT
                10.field = address
                10.wrap = <div class="street-address">|</div>
                10.if.isTrue.field = address

    			19  = TEXT
                19.field = zip
                19.wrap = <span class="postal-code">| &nbsp; </span>
                19.if.isTrue.field = zip

                20 = TEXT
                20.field = city
                20.wrap = <span class="locality">|</span>
                20.if.isTrue.field = city
            }

            #description
            40 = COA
            40 {
                required = 1
                wrap = <div class="info">|</div>
                10 = TEXT
                10 {
                    field = comments
                }
            }
        }
    }

    _CSS_DEFAULT_STYLE (
                .tx-listfeusers-pi1 .user {
                    overflow: auto;
                    position: relative;
                }

                .tx-listfeusers-pi1 .user .fn {
                    font-size: 20px;
                }

                .tx-listfeusers-pi1 .role {
                    display: none;
                }

                .tx-listfeusers-pi1 .user .contact {
                    border: 1px solid #eee;
                    padding: 10px;
                    margin: 0px 0px 10px 0px;
                    overflow: auto;
                }
                .tx-listfeusers-pi1 .user .adr {
                    border: 1px solid #eee;
                    padding: 10px;
                    margin: 0px 0px 10px 0px;
                    overflow: auto;
                }

                .tx-listfeusers-pi1 .user .photo {
                    float:right;
                    margin-left: 10px;
                }

               .tx-listfeusers-pi1 .user .contact .email, .tx-listfeusers-pi1 .user .contact .tel, .tx-listfeusers-pi1 .user .contact .fax, .tx-listfeusers-pi1 .user .contact .www {
                    float: left;
                    margin-right: 10px;
               }
            )
    }

