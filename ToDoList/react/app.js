const dataTasks = [
    {
        id: 1,
        text: "Купить хлеб",
    },
    {
        id: 2,
        text: "Освоить React",
    }
]

const Header = () => {
    return (
        <div class='header'>
            <div id='header-h1'>
                <h1>To Do List</h1>
            </div>
            <div id='header-link-main-page'>
                <a href='/'>Main page</a>
            </div>
            <hr />
        </div>
    )
}

const Main = () => {
    return (
        <div id='main'>
            {taskList}
        </div>
    )
}

const taskList = dataTasks.map((dataTask) => 
    <textarea>{dataTask.text}</textarea>
);

const Footer = () => {
    return (
        <div id='footer'>
            <h1>I am a Footer</h1>
        </div>
    )
}

const application = (
    <div>
        <Header />
        <Main />
        <Footer />
    </div>
)

ReactDOM.render(
    application,
    document.getElementById('app')
);