import React from "react";
import { BrowserRouter } from "react-router-dom";
import Layout from "./components/Layout";
import { ThemeProvider } from "styled-components";
//CSS
import "./App.css";

import { default as theme } from "./theme";

const App = (): JSX.Element => {
  return (
    <BrowserRouter>
      <ThemeProvider theme={theme}>
        <Layout />
      </ThemeProvider>
    </BrowserRouter>
  );
};

export default App;
