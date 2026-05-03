import { Head } from "@inertiajs/react";
import ContentLayout from "./Layout/ContentLayout";

const Setting = () => {
  
  return (
    <ContentLayout>
      <div className="container mx-auto p-4">
        <Head title="setting"></Head>
        <div className="grid grid-cols-2 flex-1 p-4 bg-blue-gray-100 flex-col gap-2 rounded-lg">
          settings
        </div>
      </div>
    </ContentLayout>
  )
}

export default Setting;