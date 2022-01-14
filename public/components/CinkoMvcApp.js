class CinkoMvcApp extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            head : "",
            subhead : "",
            getStartedLink : "",
            getStartedText : ""
        };
    }

    componentDidMount () {
        this.updateThisData();
    }

    async updateThisData () {
        try {
            const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ get : 'indexText' })
            };
            const res = await fetch("/api/index/text", requestOptions);
            const data = await res.json();
            this.setState({
                head : data.head,
                subhead : data.subhead,
                getStartedLink : data.getStartedLink,
                getStartedText : data.getStartedText
            });
        } catch (e) {
            console.error(e);
        }
    }

    render () {
        return (
            <div className="splash-container">
                <div className="splash">
                    <h1 className="splash-head">{this.state.head}</h1>
                    <p className="splash-subhead">
                        {this.state.subhead}
                    </p>
                    <p>
                        <a href={this.state.getStartedLink} className="pure-button pure-button-primary">
                            {this.state.getStartedText} <i className="fas fa-sign-out-alt" aria-hidden="true"></i>
                        </a>
                    </p>
                </div>
            </div>
        )
    }
}

ReactDOM.render(
    <CinkoMvcApp />, 
    document.getElementById('app')
);