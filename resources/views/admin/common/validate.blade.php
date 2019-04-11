{{--验证报错提示框--}}
@if(count($errors))
<div class="alert alert-danger">
    <ul>
        <li>
            {{$errors->first()}}
        </li>
    </ul>
</div>
@endif