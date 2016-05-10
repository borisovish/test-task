Click here to reset your password: <a href="{{ $link = url('password22/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
