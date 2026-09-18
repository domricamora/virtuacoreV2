{{-- Laravel returns 419 for an expired CSRF token, which is not a registered HTTP status
     code -- the source build hit exactly this and found Apache would not pass it through,
     so PHP set 419 and the client received a 500. Phrased for the real cause: the visitor
     left a form open too long, which is not an error they did anything to cause. --}}
<x-errors.layout
    code="419"
    title="That form timed out"
    message="The page sat open long enough for its security token to expire. Go back, reload, and send it again -- nothing you typed was lost from our side." />
