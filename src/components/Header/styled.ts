import styled from "styled-components";

export const Header = styled.header`
  overflow: hidden;
  background-color: ${props => props.theme.color.grey.light100};
  border-bottom: 1px solid ${props => props.theme.color.grey.light200};
`;

export const HeaderInnerWrapper = styled.div`
  max-width: 1140px;
  padding-left: 1rem;
  padding-right: 1rem;
  margin-left: auto;
  margin-right: auto;
  @media (min-width: 768px) {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 0;
    padding-bottom: 0;
  }
`;

export default {
  Header,
  HeaderInnerWrapper,
};
