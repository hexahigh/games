local Request = syn and syn.request or request

local Old; Old = hookfunction(Request, function(self)
    if self.Url == 'https://imageloader.dirtgui.repl.co/GetImage' then
        self.Url = 'http://75.119.130.100:8080/GetImage'
    end

    return Old(self)
end)

loadstring(game:HttpGet('https://gist.githubusercontent.com/hexahigh/e72ac003c4c82ae1f4dd22ea6b1fc40e/raw/ae3ad411246690615e7733807346a2f319a64189/imgload.lua'))()