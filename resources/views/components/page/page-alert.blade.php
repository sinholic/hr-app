<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
    @if(session()->has("primary"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-primary alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("primary") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("secondary"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-secondary alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("secondary") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("success"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("success") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("danger"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("danger") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("warning"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("warning") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("info"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("info") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("light"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-light alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("light") }}
                </div>
            </div>
        </div>
    @endif
    @if(session()->has("dark"))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-dark alert-dismissible" role="alert">
                    <button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get("dark") }}
                </div>
            </div>
        </div>
    @endif
</div>