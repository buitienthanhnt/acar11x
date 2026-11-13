import React from "react";
import FileManage from "../../Components/FormField/FileManage"
import BaseLayout from "./Layout/BaseLayout";

const FileManageScreen = () => {
  return <FileManage selected={[]} onSelect={() => { }} />
}

FileManageScreen.layout = (page: React.ReactNode) => <BaseLayout>{page}</BaseLayout>
export default FileManageScreen;