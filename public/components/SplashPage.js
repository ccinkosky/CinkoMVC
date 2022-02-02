function SplashPage (props) {

    function navigate (loc) {
        props.navigate(loc);
    }

    return (
        <div className="component-container">
            <div className="splash-container">
                <div className="splash">
                    <h1 className="splash-head">CinkoMVC</h1>
                    <div className="markdown-body splash-body" style={{textAlign: "left"}}>
                        A light weight PHP MVC framework with React via CDN setup on the front end.
                        Created with simplicity in mind, it's an easy tool to get associated with MVC
                        frameworks and/or React while still being able to work with a good old LAMP stack.
                        The front end is currently setup as a single page React app while the back end acts
                        as an API that serves up JSON for the front end to consume.
                    </div>
                    <p>
                        <a className="get-started pure-button pure-button-primary" href="https://github.com/ccinkosky/CinkoMVC" style={{marginRight:"10px"}}>
                            Visit Repo <i class="fab fa-github"></i>
                        </a>
                        <Link className="get-started pure-button pure-button-primary" onClick={()=>navigate("/README.md")}>
                            Get Started <i className="fas fa-sign-out-alt" aria-hidden="true"></i>
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}