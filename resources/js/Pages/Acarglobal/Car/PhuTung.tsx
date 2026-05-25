import { Head } from "@inertiajs/react";
import ContentLayout from "../Layout/ContentLayout";
import { Button } from "@material-tailwind/react";

export default function PhuTung() {

  return (
    <ContentLayout>
      <Head >
        <title>phụ tùng</title>
      </Head>
      <div className="container mx-auto p-4">
        <div>Danh sách phụ tùng</div>
        <Button>create phụ tùng</Button>
      </div>
    </ContentLayout>
  )
}