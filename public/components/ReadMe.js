class ReadMe extends React.Component {

    constructor (props) {
        super(props);
        this.state = { readMe : "" };        
        this.navigate = this.props.navigate;
        this.mounted = false;
    }

    componentDidMount () {
        this.updateReadMe();
    }

    async updateReadMe () {
        try {
            var md = window.markdownit();
            const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fetch : 'readMe' })
            };
            const res = await fetch("/api/index/read_me", requestOptions);
            const data = await res.json();
            this.setState({ readMe : md.render(data.readMe) });
            if (!this.mounted) {
                $(".component-container").fadeIn(1000,function () {
                    this.mounted = true;
                    hljs.highlightAll();
                });
            }
        } catch (e) {
            console.log(e);
        }
    }

    render () {
        return (
            <div className="component-container">
                <div className="readme-container">
                    <div className="readme">
                        <div className="readme-content">
                        <Link className="home-button pure-button pure-button-primary" onClick={()=>this.navigate("/")}>
                            Home
                        </Link>
                        <div 
                            className="readme-content markdown-body" 
                            dangerouslySetInnerHTML={{ __html : this.state.readMe }} 
                        />
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}