const Link = ReactRouterDOM.Link;
const Route = ReactRouterDOM.Route;
const useHistory = ReactRouterDOM.useHistory;

function CinkoMvcApp () {

    const history = useHistory();

    function navigate (loc) {
        $(".component-container").fadeOut(300,function(){
            history.push(loc);
        });
    }

    return (
        <>
            <Route exact path="/">
                <SplashPage navigate={navigate} />
            </Route>
            <Route path="/README.md">
                <ReadMe navigate={navigate} />
            </Route>
        </>
    )
}

ReactDOM.render((
    <ReactRouterDOM.HashRouter>
        <CinkoMvcApp />
    </ReactRouterDOM.HashRouter>
    ), document.getElementById('app')
);