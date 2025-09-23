<style>
  .custom-width{
    width: 70%;
    background-color: transparent;
    border: none;
  }
  .card-bodys{
    background-color: #e7e6e6c5;
    border-radius: 0.5rem;
    padding: 1rem;
  }
  .custom-input{
    padding-top: 0.5rem;
  }
  .options-toggle{
    cursor: pointer;
    font-weight: bold;
    padding: 0 5px;
    margin-left: 0.5rem;
    position: relative;
  }
  .custom_bg{
    background-color: rgba(231, 230, 230, 0.9);
    border-radius: 0.5rem;
    padding: 0.25rem;
    margin-top: 10px;
    position: relative;
  }
  .options-menu{
    display: none;
    margin-left: 40px;
    position: absolute;
    top: 20px; 
    right: 0;
    width: 80px;
    background: white;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    padding: 5px 10px;
    z-index: 100;
  }
  .options-menu a{
    display: block;
    padding: 5px;
    text-decoration: none;
    color: black;
  }
</style>
<div class="bg_color_section">
  <div class="card custom-width mx-auto ">
    <div class="card-bodys " id="about-section">
      <h1 class="fs-1 pt-2 pb-2 text-center">Comment</h1>
      <form id="commentForm" action="{{ url('add_comment') }}" method="POST" class="text-center">
        @csrf
        <textarea class="w-75 rounded-1 custom-input" name="comment" placeholder="Comment something here" ></textarea>
        <br>
        <input type="submit" class="btn btn-primary custom-input" value="Comment">
      </form>
      
      <div id="comment-list">
        <p class="fs-4 pt-3 text-center">All Comments</p>
        @foreach ($comment as $item)
            <div class="ps-5 custom_bg" id="comment-{{ $item->id }}">
              <b>{{ $item->name }} : </b>
              <span>{{ $item->comment }}</span><br>
              <a class="text-decoration-none text-primary" href="javascript:void(0)" onclick="reply(this)" data-CommentId="{{ $item->id }}">Reply</a>
              <span class="options-toggle" onclick="toggleOptions(this)">⋮</span>
              <div class="options-menu">
                <a 
                href="javascript:void(0)"
                onclick="toggleEdit('comment-{{ $item->id}}')"
                class="text-warning me-2">
                Edit
                </a>
                <a 
                class="text-danger delete-comment"
                href="javascript:void(0)"
                data-id="{{ $item->id }}"
                >Delete</a>
              </div>
              <div id="edit-form-comment-{{ $item->id }}" class="mt-2 d-none">
                <form action="{{ url('update_comment', $item->id) }}" method="POST">
                  @csrf
                  
                  <textarea name="comment_change" class="form-control w-50" rows="2">{{ $item->comment }}</textarea>
                  <button type="submit" class="btn btn-success btn-sm mt-2">Update</button>
                  <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="toggleEdit('comment-{{ $item->id }}')">Cancel</button>
                </form>
              </div>
                @foreach ($reply as $items)
                  @if($items->comment_id == $item->id)
                   <div class="ps-3" id="reply-{{ $items->id }}">
                      <b> ↳ {{ $items->name }} : </b>
                      <span>{{ $items->reply }}</span><br>
                      <a class="text-decoration-none text-primary" href="javascript:void(0)" onclick="reply(this)" data-CommentId="{{ $item->id }}">Reply</a>
                      <span class="options-toggle" onclick="toggleOptions(this)">⋮</span>
                      <div class="options-menu">
                        <a 
                        href="javascript:void(0)"
                        onclick="toggleEdit('reply-{{ $items->id}}')"
                        class="text-warning me-2">
                        Edit
                      </a>
                        <a 
                          class="text-danger reply-comment"
                          href="javascript:void(0)"
                          data-id="{{ $items->id }}"
                          >Delete</a>
                      </div>
                   </div>
                   <div id="edit-form-reply-{{ $items->id }}" class="mt-2 d-none">
                    <form  action="{{ url('update_reply',$items->id) }}" method="POST">
                      @csrf
                      <textarea name="reply_change" class="form-control w-50" rows="2">{{ $items->reply }}</textarea>
                      <button type="submit" class="btn btn-success btn-sm mt-2">Update</button>
                      <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="toggleEdit('reply-{{$items->id }}')">Cancel</button>
                    </form>

                   </div>
                   @endif
                @endforeach
            </div>
        @endforeach
              

        </div>
        <div class="ps-5 d-none repdiv" >
          <form id="replyForm" action="{{ url('add_reply') }}" method="POST">
            @csrf
            <input id="commentId" name="commentId"  type="hidden">
            <textarea class="w-25 rounded-1 custom-input" name="reply" placeholder="Write something here"></textarea>
            <input type="submit" class="btn btn-primary" value="reply">
            <a href="javascript:void(0)" class="btn btn-danger" onclick="reply_close(this)">Close</a>
            </form>
        </div>  
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).on('submit', '#commentForm', function(e){
    e.preventDefault();
    let form = $(this);
    let formData = form.serialize();
    $.ajax({
      url: form.attr("action"),
      type: 'POST',
      data: formData,
      success: function(response){
        if(response.status === 'success'){
          $("#comment-list p").after(`
          <div class="ps-5 custom_bg" id="comment-${response.comment.id}">
              <b>${response.user} : </b>
              <span>${response.comment.comment}</span><br>
              <a class="text-decoration-none text-primary" href="javascript:void(0)" onclick="reply(this)" data-CommentId="${response.comment.id}">Reply</a>
              <span class="options-toggle" onclick="toggleOptions(this)">⋮</span>
              <div class="options-menu">
                <a 
                href="javascript:void(0)"
                onclick="toggleEdit('comment-${response.comment.id}')"
                class="text-warning me-2">
                Edit
                </a>
                <a 
                class="text-danger delete-comment"
                href="javascription:void(0)"
                data-id="${response.comment.id}"
                >Delete</a>
              </div>
          `);
          $("textarea[name='comment']").val("")
        }
      },
    });
  });
  $(document).on('submit','#replyForm', function(e){
    e.preventDefault();
    let form = $(this);
    let formdata = form.serialize();
    $.ajax({
      url: form.attr('action'),
      type: 'POST',
      data : formdata,
      success: function(response){
        if(response.status === 'success'){
          let id = response.reply.comment_id;
          $(`#comment-${id}`).append(`
          <div class="ps-3" id="reply-${response.reply.id}">
                      <b> ↳ ${response.user} : </b>
                      <span>${response.reply.reply}</span><br>
                      <a class="text-decoration-none text-primary" href="javascript:void(0)" onclick="reply(this)" data-CommentId="${id}">Reply</a>
                      <span class="options-toggle" onclick="toggleOptions(this)">⋮</span>
                      <div class="options-menu">
                        <a 
                        href="javascript:void(0)"
                        onclick="toggleEdit('reply-${response.reply.id}')"
                        class="text-warning me-2">
                        Edit
                      </a>
                       <a 
                        class="text-danger delete-reply"
                        href="javascription:void(0)"
                        data-id="${response.reply.id}"
                        >Delete</a>
                      </div>
                   </div>
          `);
           $("textarea[name='reply']").val("");   
           $(".repdiv").addClass("d-none");  
        }
      }
    })
  });
  $(document).on('click', '.delete-comment', function(e){
    let form = $(this)
    let formdata = form.data('id');
    if(confirm('Are  you sure want to delete this comment?')){
      $.ajax({
        url: `delete_comment/${formdata}`,
        type:'DELETE',
        data:{
          _token:"{{ csrf_token() }}"
        },
        success: function(response){
          if(response.status === 'success'){
            $(`#comment-${formdata}`).remove()
          }
        }
      });
    }
  });
  $(document).on('click', '.reply-comment', function(e){
    let form = $(this)
    let formdata = form.data('id');
    if(confirm('Are  you sure you want to delete this reply?')){
      $.ajax({
        url: `delete_reply/${formdata}`,
        type:'DELETE',
        data:{
          _token:"{{ csrf_token() }}"
        },
        success: function(response){
          if(response.status === 'success'){
            $(`#reply-${formdata}`).remove()
          }
        }
      });
    }
  });

 function reply(caller) {
    const $caller = $(caller);
    const $repdiv = $('.repdiv').first(); 
    $('#commentId').val( $caller.attr('data-CommentId') );

    $repdiv.insertAfter($caller);
    $repdiv.removeClass('d-none');

    $('.options-menu').hide();

    const $parendiv = $caller.closest('.custom_bg, .ps-5');
    if ($parendiv.length) {
      $parendiv.find('.options-toggle').addClass('d-none');

      $repdiv.data('parentComment', $parendiv); 
    }
  }

  function reply_close(caller) {
    const $repdiv = $(caller).closest('.repdiv'); 
    const $parentComment = $repdiv.data('parentComment'); 

    if ($parentComment && $parentComment.length) {
      $parentComment.find('.options-toggle').removeClass('d-none');
    }

    $repdiv.addClass('d-none');
  }
  function toggleOptions(e){
    document.querySelectorAll('.options-menu').forEach(m => m.style.display = 'none');
    let menu = e.nextElementSibling;
    if( menu .style.display === 'block'){
      menu.style.display = 'none';
    }
    else{
      menu.style.display = 'block';
    }
  }
  document.addEventListener('click', function(e){
    if(!e.target.classList.contains('options-toggle')){
      document.querySelectorAll('.options-menu').forEach(m => m.style.display = 'none');
    }
  })
  function toggleEdit(id){
    let menu = document.getElementById('edit-form-'+id);
    menu.classList.toggle('d-none')
  }
</script>

