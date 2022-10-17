import styled from "styled-components";

export const Ul = styled.ul`
  display: flex;
  list-style: none;
  a {
    color: ${(props) => props.theme.color.grey.dark100};
    display: block;
    padding: 1rem 1.2rem;
    font-size: .9rem;
    transition: background-color 0.4s;

    &:hover, &.active {
      background-color: ${(props) => props.theme.color.grey.light200};
    }
  }
  li {

    ul {
      background-color: ${(props) => props.theme.color.grey.light100};
      display: none;
      margin: 0;
      position: absolute;

      a {
        font-size: 0.8rem;
      }
    }

    &:hover {

      ul {
        display: flex;
      }
    }
  }
`;

export const Nav = styled.nav`
  background-color: ${(props) => props.theme.color.grey.light100};
  display: block;
`;

export default {
  Ul,
  Nav,
};
