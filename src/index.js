import React from "react";
import ReactDOM from "react-dom";
import { Container, Header } from "semantic-ui-react";
import {
    BrowserRouter as Router,
    Switch,
    Route,
    Link
} from "react-router-dom";

import PeopleResultsList from "./views/PeopleResultsList";
import GroupsResultsList from "./views/GroupsResultsList";

const App = ({ children }) => (
  <Container style={{ margin: 20 }}>
    <Header as="h3"><span role="img" aria-label="logo">⛵️</span> Breeze Church Management </Header>

    {children}
  </Container>
);

const styleLink = document.createElement("link");
styleLink.rel = "stylesheet";
styleLink.href = "https://cdn.jsdelivr.net/npm/semantic-ui/dist/semantic.min.css";
document.head.appendChild(styleLink);

ReactDOM.render(
  <App>
      <Router>
          <div>
              <nav>
                  <ul>
                      <li>
                          <Link to="/people">People</Link>
                      </li>
                      <li>
                          <Link to="/groups">Groups</Link>
                      </li>
                  </ul>
              </nav>

              {/* A <Switch> looks through its children <Route>s and
            renders the first one that matches the current URL. */}
              <Switch>
                  <Route path="/people">
                      <PeopleResultsList />
                  </Route>
                  <Route path="/groups">
                      <GroupsResultsList />
                  </Route>
                  <Route path="/">
                      <PeopleResultsList />
                  </Route>
              </Switch>
          </div>
      </Router>
  </App>,
  document.getElementById("root")
);
