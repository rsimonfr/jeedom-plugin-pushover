
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/


 $("#table_cmd").delegate(".listCmdInfo", 'click', function () {
    var el = $(this).closest('.cmd').find('.cmdAttr[data-l2key=' + $(this).attr('data-input') + ']');
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
        el.atCaret('insert', result.human);
    });
});


function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }



    var pushoverpriority = '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="priority"  >';
    pushoverpriority  += '<option value="-2">Priorite la plus basse</option>';
    pushoverpriority  += '<option value="-1">Priorite basse</option>';
    pushoverpriority  += '<option value="0">Priorite normale</option>';
    pushoverpriority  += '<option value="1">Priorite haute</option>';
    pushoverpriority  += '<option value="2">Emergency</option>';
    pushoverpriority  += '</select>';


    var pushoversound = '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="sound" >';
    pushoversound += '<option value="pushover">pushover</option>';
    pushoversound += '<option value="bike">Bike</option>';
    pushoversound += '<option value="bugle">Bugle</option>';
    pushoversound += '<option value="cashregister">Cash Register</option>';
    pushoversound += '<option value="classical">Classical</option>';
    pushoversound += '<option value="cosmic">Cosmic</option>';
    pushoversound += '<option value="falling">Falling</option>';
    pushoversound += '<option value="gamelan">Gamelan</option>';
    pushoversound += '<option value="incoming">Incoming</option>';
    pushoversound += '<option value="intermission">Intermission</option>';
    pushoversound += '<option value="magic">Magic</option>';
    pushoversound += '<option value="mechanical">Mechanical</option>';
    pushoversound += '<option value="pianobar">Piano Bar</option>';
    pushoversound += '<option value="siren">Siren</option>';
    pushoversound += '<option value="spacealarm">Space Alarm</option>';
    pushoversound += '<option value="tugboat">Tug Boat</option>';
    pushoversound += '<option value="alien">Alien Alarm (long)</option>';
    pushoversound += '<option value="climb">Climb (long)</option>';
    pushoversound += '<option value="persistent">Persistent (long)</option>';
    pushoversound += '<option value="echo">Pushover Echo (long)</option>';
    pushoversound += '<option value="updown">Up Down (long)</option>';
    pushoversound += '<option value="none">None (silent)</option>';
    pushoversound += '</select>';

    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name"></td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="token"></td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="user"></td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="device"></td>';
    tr += '<td>' + pushoverpriority + '</td>' ;
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="retry"></td>';
    tr += '<td><input class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="expire"></td>';
    tr += '<td>' + pushoversound + '</td>' ; 
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="type" value="action" style="display : none;">';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="subType" value="message" style="display : none;">';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
}
