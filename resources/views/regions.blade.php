<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ข้อมูลจังหวัดในประเทศไทย</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <script src="{{asset('js/jQuery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <style media='screen'>
        .box {
            width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <h1 class="display-3 text-danger text-center mb-5">Laravel Dynamic Dropdown </h1>
    <div class="container box">
        <h3 class='text-center'>ข้อมูลจังหวัดในประเทศไทย</h3>
        <div class="form-group">
            <label for="geographies">ภาค</label>
            <select name="geographies" id="geographies" class="form-control geographies">
                <option value="">เลือกภาคของท่าน</option>
                @foreach($list as $row)
                    <option value="{{$row->id}}">{{$row->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="provinces">จังหวัด</label>
            <select name="provinces" id="provinces" class="form-control provinces">
                <option value="">เลือกจังหวัดของท่าน</option>
            </select>
        </div>
        <div class="form-group">
            <label for="amphures">อำเภอ</label>
            <select id="amphures" name="amphures" class="form-control amphures">
                <option value="">เลือกอำเภอของท่าน</option>
            </select>
        </div>
        <div class="form-group">
            <label for="geographies">ตำบล</label>
            <select id="districts" name="districts" class="form-control districts">
                <option value="">เลือกตำบลของท่าน</option>
            </select>
        </div>
    </div>
    {{csrf_field()}} <!-- ต้องมี csrf_token เพื่อสำหรับส่งค่า -->
    <script>
        $(document).ready(function () {
            $('select').change(function() {
                const selectCurrent = $(this);
                const selectList = selectCurrent
                                    .closest('body')
                                    .find('select');
                
                let selectNext;
                $(selectList).each(function (ind, element) {
                    if($(element).attr('id') == selectCurrent.attr('id')) {
                        selectNext = $(selectList[ind+1]);
                        return false;
                    }
                });

                if(selectCurrent.val() != '' && selectNext.length != 0) {
                    
                    let selectId = selectCurrent.val();
                    let areaName = selectCurrent.attr('name');
                    let areaNextName = selectNext.attr('name');
                    let _token = $('input[name="_token"').val();

                    $.ajax({
                        url: "{{route('dropdown.fetch')}}",
                        method: "POST",
                        data: {
                            select: selectId,
                            curr_name: areaName,
                            next_name: areaNextName,
                            _token: _token
                        },
                        success: function(result) {
                            $(selectNext).append(result);
                        }

                    });

                } else {

                    let boxSelect = $(selectCurrent).closest('div');
                    let selectNextList = boxSelect.nextAll().find('select');
                    selectNextList.find('option:not(:first-child)').remove();

                }
            });
        });
    </script>
</body>
</html>