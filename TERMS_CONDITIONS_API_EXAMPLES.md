# Terms and Conditions API - أمثلة الاستخدام

## ✅ الـ API يعمل بنجاح!

الـ API يعيد البيانات **بالعربي والإنجليزي معاً** في نفس الاستجابة.

---

## 📝 أمثلة CURL

### 1. Get Active Terms (الشروط النشطة)

```bash
curl -X GET "http://your-domain.com/api/terms-and-conditions" \
  -H "Accept: application/json"
```

**Response:**

```json
{
    "success": true,
    "message": "تم جلب الشروط والأحكام بنجاح",
    "status_code": 200,
    "data": {
        "id": 13,
        "title_ar": "الشروط والأحكام - الإصدار 1.0",
        "title_en": "Terms and Conditions - Version 1.0",
        "content_ar": "# الشروط والأحكام\n\n## 1. المقدمة...",
        "content_en": "# Terms and Conditions\n\n## 1. Introduction...",
        "version": "1.0",
        "is_active": true,
        "effective_date": "2026-02-01T16:58:57+00:00",
        "created_at": "2026-02-01T16:58:57+00:00",
        "updated_at": "2026-02-01T16:58:57+00:00"
    }
}
```

---

### 2. Get All Versions (جميع الإصدارات)

```bash
curl -X GET "http://your-domain.com/api/terms-and-conditions/versions" \
  -H "Accept: application/json"
```

---

### 3. Get Specific Version (إصدار محدد)

```bash
curl -X GET "http://your-domain.com/api/terms-and-conditions/13" \
  -H "Accept: application/json"
```

---

## 📱 استخدام في JavaScript/React Native

### Fetch API

```javascript
// Get active terms
fetch("http://your-domain.com/api/terms-and-conditions", {
    method: "GET",
    headers: {
        Accept: "application/json",
    },
})
    .then((response) => response.json())
    .then((data) => {
        console.log("Arabic Title:", data.data.title_ar);
        console.log("English Title:", data.data.title_en);
        console.log("Arabic Content:", data.data.content_ar);
        console.log("English Content:", data.data.content_en);
    });
```

### Axios

```javascript
import axios from "axios";

const getTerms = async () => {
    try {
        const response = await axios.get(
            "http://your-domain.com/api/terms-and-conditions",
            {
                headers: {
                    Accept: "application/json",
                },
            },
        );

        const { data } = response.data;

        // Use based on user language
        const userLanguage = "ar"; // or 'en'
        const title = userLanguage === "ar" ? data.title_ar : data.title_en;
        const content =
            userLanguage === "ar" ? data.content_ar : data.content_en;

        return { title, content, version: data.version };
    } catch (error) {
        console.error("Error fetching terms:", error);
    }
};
```

---

## 🔧 PowerShell (Windows)

```powershell
$response = Invoke-WebRequest -Uri "http://your-domain.com/api/terms-and-conditions" `
  -Method GET `
  -Headers @{"Accept"="application/json"} `
  -UseBasicParsing

$data = $response.Content | ConvertFrom-Json
Write-Host "Arabic Title: $($data.data.title_ar)"
Write-Host "English Title: $($data.data.title_en)"
```

---

## 📊 Response Structure

```typescript
interface TermsResponse {
    success: boolean;
    message: string;
    status_code: number;
    data: {
        id: number;
        title_ar: string; // العنوان بالعربي
        title_en: string; // العنوان بالإنجليزي
        content_ar: string; // المحتوى بالعربي
        content_en: string; // المحتوى بالإنجليزي
        version: string; // رقم الإصدار
        is_active: boolean; // هل نشط؟
        effective_date: string;
        created_at: string;
        updated_at: string;
    };
}
```

---

## ✨ Features

✅ **No Authentication Required** - بدون مصادقة
✅ **Bilingual** - عربي وإنجليزي معاً
✅ **Version Control** - إدارة الإصدارات
✅ **Markdown Support** - دعم Markdown في المحتوى
✅ **Fast Response** - استجابة سريعة

---

## 🎯 Use Cases

### 1. Display in Mobile App

```javascript
const TermsScreen = ({ userLanguage }) => {
    const [terms, setTerms] = useState(null);

    useEffect(() => {
        fetch("http://your-domain.com/api/terms-and-conditions")
            .then((res) => res.json())
            .then((data) => setTerms(data.data));
    }, []);

    if (!terms) return <Loading />;

    const title = userLanguage === "ar" ? terms.title_ar : terms.title_en;
    const content = userLanguage === "ar" ? terms.content_ar : terms.content_en;

    return (
        <View>
            <Text style={styles.title}>{title}</Text>
            <Markdown>{content}</Markdown>
            <Text style={styles.version}>Version: {terms.version}</Text>
        </View>
    );
};
```

### 2. Accept Terms on Registration

```javascript
const acceptTerms = async (userId) => {
    const response = await fetch(
        "http://your-domain.com/api/terms-and-conditions",
    );
    const { data } = await response.json();

    // Store that user accepted this version
    await saveUserAcceptance(userId, data.version, data.id);
};
```

### 3. Show Version History

```javascript
const VersionHistory = () => {
    const [versions, setVersions] = useState([]);

    useEffect(() => {
        fetch("http://your-domain.com/api/terms-and-conditions/versions")
            .then((res) => res.json())
            .then((data) => setVersions(data.data));
    }, []);

    return (
        <FlatList
            data={versions}
            renderItem={({ item }) => (
                <VersionItem
                    version={item.version}
                    date={item.effective_date}
                    isActive={item.is_active}
                />
            )}
        />
    );
};
```

---

## 🚀 Ready to Use!

الـ API جاهز للاستخدام في التطبيق مباشرة. جميع البيانات متوفرة بالعربي والإنجليزي في نفس الاستجابة!
