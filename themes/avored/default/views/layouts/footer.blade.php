<footer class="page-footer mt-3 pb-3 border border-bottom-0 border-left-0 border-right-0 pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="widget block block-static-block">
                    <ul class="footer links">
                        <li class="nav item">
                            <a href="#">About us</a>
                        </li>
                        <li class="nav item">
                            <a href="#">Contact us</a>
                        </li>
                        <li class="nav item">
                            <a href="#">Customer Service</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <ul class="footer links">
                    <li class="nav item">
                        <a href="#">Privacy & Cookie Policy</a>
                    </li>
                    <li class="nav item">
                        <a href="#">Orders</a>
                    </li>
                    <li class="nav item">
                        <a href="#">Returns</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-4">
                <div class="title">
                    <strong>Newsletter</strong>
                </div>
                <div class="content">
                    <form class="navbar-form" action="{{ route('subscribe.store') }}" method="post">

                        @csrf
                        <div class="field newsletter">
                            <label class="label" for="newsletter">
                                <span>Sign Up for Our Newsletter:</span>
                            </label>
                        </div>


                        <div class="input-group">

                            <input type="text"

                                   @if($errors->has('subscriber_email'))
                                   class="form-control is-invalid"
                                   @else
                                   class="form-control"
                                   @endif

                                   placeholder="Enter your email address" name="subscriber_email"/>


                            <div class="input-group-btn">

                                <button class="btn btn-primary" type="submit">
                                    <span>Subscribe</span>
                                </button>
                            </div>


                            @if($errors->has('subscriber_email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subscriber_email') }}
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>

<footer class="row">
    <div class="container-fluid bg-dark">

        <div class="copyright text-center p-2 text-white">
            <span>Copyright &copy; {{ date('Y') }} <a href="http://avored.website" title="AvoRed Company"
                                                      target="_blank">AvoRed</a>. All rights reserved.</span>

        </div>
    </div>
</footer>