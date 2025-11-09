import BodyContent from "./Components/BodyContent";
import DupLayout from "./DupLayout";

const SingleLayout = ({ children, topMenu }) => {
    return (
        <DupLayout>
            <BodyContent content={children}></BodyContent>
        </DupLayout>
    );
}

export default SingleLayout;
