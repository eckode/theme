import React from "react";
import { Link } from "react-router-dom";

import TreeNav from "../TreeNav";

import { Header as HeaderStyled, HeaderInnerWrapper } from "./styled";

const {
  static: {
    menus: { main: mainMenu = [] },
  },
} = window.Eckode;

const Header = (): JSX.Element => {
  return (
    <HeaderStyled>
      <HeaderInnerWrapper>
        <Link to="/">Home</Link>
        <TreeNav aria-label="Global" items={mainMenu} />
      </HeaderInnerWrapper>
    </HeaderStyled>
  );
};

export default Header;
