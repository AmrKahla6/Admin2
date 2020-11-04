@foreach($comments as $comment)
    <div class="display-comment" @if($comment->parent_id != null) style="margin-left:40px;" @endif>
        <strong>{{ $comment->user->name }}</strong>
        <p>{{ $comment->comment }}</p>
        <form method="post" action="{{ route('comments.store') }}">
            @csrf
            <div class="form-group">
                <input type="hidden" name="category_id" value="{{ $category_id }}" />
                <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
            </div>

        </form>
    </div>
@endforeach
