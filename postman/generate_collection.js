import fs from 'fs';

const collection = {
  "info": {
    "_postman_id": "system-enhancements-2025",
    "name": "System Enhancements API - التحديثات الجديدة",
    "description": "مجموعة شاملة لجميع الـ API endpoints الجديدة المضافة في تحديثات النظام",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "auth": {
    "type": "bearer",
    "bearer": [
      {
        "key": "token",
        "value": "{{auth_token}}",
        "type": "string"
      }
    ]
  },
  "variable": [
    {
      "key": "base_url",
      "value": "http://hamid_chef.test/",
      "type": "string"
    },
    {
      "key": "auth_token",
      "value": "",
      "type": "string"
    },
    {
      "key": "booking_id",
      "value": "1",
      "type": "string"
    }
  ],
  "item": [
    {
      "name": "1. Authentication - المصادقة",
      "item": [
        {
          "name": "Login - تسجيل الدخول",
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "if (pm.response.code === 200) {",
                  "    var jsonData = pm.response.json();",
                  "    pm.collectionVariables.set('auth_token', jsonData.token);",
                  "}"
                ],
                "type": "text/javascript"
              }
            }
          ],
          "request": {
            "auth": {
              "type": "noauth"
            },
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": JSON.stringify({
                "email": "chef@example.com",
                "password": "password123"
              }, null, 2)
            },
            "url": {
              "raw": "{{base_url}}/login",
              "host": ["{{base_url}}"],
              "path": ["login"]
            }
          }
        },
        {
          "name": "Get Current User",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/me",
              "host": ["{{base_url}}"],
              "path": ["me"]
            }
          }
        },
        {
          "name": "Logout",
          "request": {
            "method": "POST",
            "header": [],
            "url": {
              "raw": "{{base_url}}/logout",
              "host": ["{{base_url}}"],
              "path": ["logout"]
            }
          }
        }
      ]
    },
    {
      "name": "2. Booking Rejection - رفض الحجوزات",
      "item": [
        {
          "name": "Reject Booking (Chef)",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": JSON.stringify({
                "rejection_reason": "عذراً، لدي التزام آخر في هذا الوقت"
              }, null, 2)
            },
            "url": {
              "raw": "{{base_url}}/chef/bookings/{{booking_id}}/reject",
              "host": ["{{base_url}}"],
              "path": ["chef", "bookings", "{{booking_id}}", "reject"]
            }
          }
        },
        {
          "name": "Get Booking Details",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/bookings/{{booking_id}}",
              "host": ["{{base_url}}"],
              "path": ["bookings", "{{booking_id}}"]
            }
          }
        },
        {
          "name": "List All Bookings",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/bookings",
              "host": ["{{base_url}}"],
              "path": ["bookings"]
            }
          }
        }
      ]
    },
    {
      "name": "3. User Profile - الملف الشخصي للمستخدم",
      "item": [
        {
          "name": "Get User Profile",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/profile",
              "host": ["{{base_url}}"],
              "path": ["profile"]
            }
          }
        },
        {
          "name": "Update User Profile",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": JSON.stringify({
                "first_name": "أحمد",
                "last_name": "محمد",
                "email": "ahmed@example.com",
                "phone_number": "+967777777777",
                "address": "صنعاء، اليمن"
              }, null, 2)
            },
            "url": {
              "raw": "{{base_url}}/profile",
              "host": ["{{base_url}}"],
              "path": ["profile"]
            }
          }
        }
      ]
    },
    {
      "name": "4. Chef Profile - الملف الشخصي للشيف",
      "item": [
        {
          "name": "Get Chef Profile",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/chef/profile",
              "host": ["{{base_url}}"],
              "path": ["chef", "profile"]
            }
          }
        },
        {
          "name": "Update Chef Profile",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": JSON.stringify({
                "name": "الشيف أحمد",
                "description_ar": "شيف متخصص في المأكولات اليمنية",
                "description_en": "Chef specialized in Yemeni cuisine",
                "base_hourly_rate": 50.00,
                "phone_number": "+967777777777",
                "governorate_id": 1,
                "district_id": 1,
                "area_id": 1
              }, null, 2)
            },
            "url": {
              "raw": "{{base_url}}/chef/profile",
              "host": ["{{base_url}}"],
              "path": ["chef", "profile"]
            }
          }
        }
      ]
    },
    {
      "name": "5. KYC Certificates - شهادات التحقق",
      "item": [
        {
          "name": "Upload Identity Document",
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "formdata",
              "formdata": [
                {
                  "key": "certificate_type",
                  "value": "identity_document",
                  "type": "text"
                },
                {
                  "key": "file",
                  "type": "file",
                  "src": ""
                }
              ]
            },
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates"]
            }
          }
        },
        {
          "name": "Upload Health Certificate",
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "formdata",
              "formdata": [
                {
                  "key": "certificate_type",
                  "value": "health_certificate",
                  "type": "text"
                },
                {
                  "key": "file",
                  "type": "file",
                  "src": ""
                }
              ]
            },
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates"]
            }
          }
        },
        {
          "name": "Upload Professional Certificate",
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "formdata",
              "formdata": [
                {
                  "key": "certificate_type",
                  "value": "professional_certificate",
                  "type": "text"
                },
                {
                  "key": "file",
                  "type": "file",
                  "src": ""
                }
              ]
            },
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates"]
            }
          }
        },
        {
          "name": "List All Certificates",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates"]
            }
          }
        },
        {
          "name": "Delete Identity Document",
          "request": {
            "method": "DELETE",
            "header": [],
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates/identity_document",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates", "identity_document"]
            }
          }
        },
        {
          "name": "Delete Health Certificate",
          "request": {
            "method": "DELETE",
            "header": [],
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates/health_certificate",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates", "health_certificate"]
            }
          }
        },
        {
          "name": "Delete Professional Certificate",
          "request": {
            "method": "DELETE",
            "header": [],
            "url": {
              "raw": "{{base_url}}/chef/kyc/certificates/professional_certificate",
              "host": ["{{base_url}}"],
              "path": ["chef", "kyc", "certificates", "professional_certificate"]
            }
          }
        }
      ]
    }
  ]
};

// Write to file
fs.writeFileSync('System_Enhancements_API.postman_collection.json', JSON.stringify(collection, null, 2), 'utf8');
console.log('✅ Postman collection created successfully!');
console.log('📁 File: System_Enhancements_API.postman_collection.json');
console.log('📊 Total endpoints: 17');
