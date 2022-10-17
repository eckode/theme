import React from "react";

import TreeNav from "./TreeNav";

const {
  static: {
    menus: { footer: footerMenu = [] },
  },
} = window.Eckode;

const Footer = (): JSX.Element => {
  return (
    <footer>
      <TreeNav items={footerMenu} />
    </footer>
  );
};

export default Footer;
