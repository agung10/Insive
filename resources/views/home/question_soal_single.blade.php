<div class="col-12">
  <div id="loader-wrapper">
      <div id="loader"></div>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <h1>{{$question->no_question}}. {{$question->question}}</h1>
      <span class="invalid-feedback" id="errorMsg">
          <strong>You must select first!</strong>
      </span>
      <ul>
          @if ($question->category == 'single')
          @foreach ($question->options as $item)
          <li>
              <input type="radio" id="qfirst_{{$item->id}}" class="option_id" name="option_id" value="{{$item->id}}">
              <label for="qfirst_{{$item->id}}">{{$item->text}}</label>
          </li>
          @endforeach
          @elseif ($question->category == 'multiple')
          @foreach ($question->options as $item)
          <li>
              <input type="checkbox" id="qfirst_{{$item->id}}" name="option_id[]" value="{{$item->id}}">
              <label for="qfirst_{{$item->id}}"><i class='bx bx-check'></i> {{$item->text}}</label>
          </li>
          @endforeach
          @elseif ($question->category == 'dropdown')
          <select class="form-control select2 select_option_id text-black">
              @foreach ($allCities as $item)
              <option >{{$item['city_name']}}</option>
              @endforeach
          </select>
          @endif
      </ul>
    </div>
  </div>
  @if($question->id <> 1)
    <a href="javascript:void(0);" class="carousel-control-prev" id="btnPrevious" data-id="{{$question->id}}">
      <span class="carousel-control-prev-icon" aria-hidden="false">
        <i class='bx bx-left-arrow-alt'></i>
      </span>
      <span class="sr-only">Prev</span>
    </a>
  @endif
  <a class="carousel-control-next" id="btnNext" href="javascript:void(0)" role="button" data-slide="next" data-id="{{$question->id}}">
    <span class="carousel-control-next-icon" aria-hidden="true">
        <i class='bx bx-right-arrow-alt'></i>
    </span>
    <span class="sr-only">Next</span>
  </a>
  <strong style="color: #636363;">
    Note: If you back to previous question, the current answer will be remove.
    But your answer on another previous question still be there
  </strong>
</div>
