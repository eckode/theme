import React from "react";

import TreeNavigation from "./TreeMenu";

const {
  static: {
    menus: { main: mainMenuModel },
  },
} = window.Eckode;

const Header = (): JSX.Element => {
  return (
    <header>
      <nav>
        <TreeNavigation items={mainMenuModel} />
      </nav>
    </header>
  );
};

export default Header;
